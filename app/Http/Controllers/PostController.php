<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use App\Enums\PostGroup;
use App\Models\Category;
use App\Models\Feedback;
use App\Jobs\PostTranslate;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\ProcessPostImages;
use App\Enums\NotificationGroup;
use App\Http\Requests\PostRequest;
use App\Jobs\MailerProcessNewPost;
use Illuminate\Support\Facades\Bus;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class PostController extends Controller
{
    public function show(Request $request, Post $post)
    {
        $user = auth()->user();
        $hasChat = $user?->hasChatWith($post->user_id);

        if (($post->is_trashed || !$post->is_active) && $post->user_id != $user?->id) {
            return view('posts.inactive', compact('post', 'hasChat'));
        }

        $authorPosts = Post::query()
            ->where('user_id', $post->user_id)
            ->visible()
            ->where('id', '!=', $post->id)
            ->inRandomOrder()
            ->limit(20)
            ->get();
        $simPosts = $post->category
            ->postsAll()
            ->visible()
            ->whereNotIn('id', $authorPosts->pluck('id')->toArray())
            ->where('id', '!=', $post->id)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $data = [
            'post' => $post,
            'authorPosts' => $authorPosts,
            'simPosts' => $simPosts,
            'hasChat' => $hasChat
        ];

        return view('posts.show', $data);
    }

    public function create(Request $request, $type=null)
    {
        list($categsFirstLevel, $categsSecondLevel, $categsThirdLevel) = Category::getLevels($type != 'service');
        $view = $type == 'service' ? 'posts.create-service' : 'posts.create';

        return view($view, compact('categsFirstLevel', 'categsSecondLevel', 'categsThirdLevel'));
    }

    public function store(PostRequest $request, TranslationService $translator)
    {
        $user = auth()->user();

        if ($this->reachedPostsLimit($user)) {
            return $this->subscriptionErrorResponse(2);
        }

        $input = $request->validated();
        $textLocale = $translator->detectLanguage($input['title'] . '. ' . $input['description']);
        $input['duration'] = 'unlim';
        $input['origin_lang'] = $textLocale;
        $input['status'] = 'pending';
        $input['is_active']= true;
        $input['is_tba'] = $input['is_tba']??false;
        $input['slug'] = [
            $textLocale => makeSlug($input['title'], Post::allSlugs())
        ];
        $input['title'] = [
            $textLocale => $input['title']
        ];
        $input['description'] = [
            $textLocale => $input['description']
        ];
        $post = $user->posts()->create($input);

        if ($post->group == PostGroup::EQUIPMENT) {
            $post->saveCosts($input);
        }

        $post->saveTranslations($input);
        $images = $post->addAttachment(array_reverse($input['images']??[]), 'images', true);
        $post->addAttachment($input['documents']??[], 'documents');

        $chain = [new PostTranslate($post)];

        if (!Setting::get('hide_pending_posts', true, true)) {
            $chain[] = new MailerProcessNewPost($post);
        }

        Bus::chain($chain)->dispatch();
        ProcessPostImages::dispatch($images);

        flash(trans('messages.posts.created'));

        return $this->jsonSuccess('', [
            'redirect' => route('posts.show', $post)
        ]);
    }

    public function translationsEdit(Request $request, Post $post)
    {
        return view('posts.translations', compact('post'));
    }

    public function translationsUpdate(Request $request, Post $post)
    {
        $input = $request->validate([
            'auto_translate' => ['nullable'],
            'rerun-translator' => ['nullable'],
            'title' => ['required_without:auto_translate', 'array'],
            'title.*' => ['required_without:auto_translate', 'string', 'max:255'],
            'description' => ['required_without:auto_translate', 'array'],
            'description.*' => ['required_without:auto_translate', 'string', 'max:5000'],
        ]);

        $input['auto_translate'] ??= false;

        if (!$input['auto_translate']) {
            $post->update($input);
            $allPostSlugs = Post::allSlugs();
            foreach ($input['title'] as $locale => $title) {
                if ($title != $post->translated('title', $locale)) {
                    $input['slug'][$locale] = makeSlug($title, $allPostSlugs);
                }
            }
            $post->saveTranslations($input);
        } else if ($input['rerun-translator']??false) {
            $cKay = 'users.' . auth()->id() . '.posts.translation-request';
            $spam = cache()->get($cKay, false);

            if ($spam) {
                \Log::info("POST TRANSLATION #$post->id: user spammed auto-translation");

                return $this->jsonError(trans('messages.posts.translations-spam'));
            }

            cache()->put($cKay, true, 60);
            $post->update($input);

            PostTranslate::dispatch($post);
        }

        flash(trans('messages.posts.translations-updates'));

        return $this->jsonSuccess('', [
            'redirect' => route('posts.edit', $post)
        ]);
    }

    public function translationsReport(Request $request, Post $post)
    {
        $user = auth()->user();

        Feedback::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'subject' => "Post #$post->id translations are invalid",
            'text' => 'User reported invalid auto-translations of hist post.'
        ]);

        return $this->jsonSuccess(trans('messages.posts.invalid-translations-reported'));
    }

    public function edit(Request $request, Post $post)
    {
        list($categsFirstLevel, $categsSecondLevel, $categsThirdLevel) = Category::getLevels($post->group == PostGroup::EQUIPMENT);
        $activeLevels = array_column($post->category->parents(true), 'id');
        $view = $post->group == PostGroup::SERVICE ? 'posts.edit-service' : 'posts.edit';

        return view($view, compact('post', 'categsFirstLevel', 'categsSecondLevel', 'categsThirdLevel', 'activeLevels'));
    }

    public function update(PostRequest $request, Post $post, TranslationService $translator)
    {
        // dlog("PostController@update. START. post #$post->id", $request->all()); //! LOG

        $user = auth()->user();
        $input = $request->validated();
        $textLocale = $translator->detectLanguage($input['title'] . '. ' . $input['description']);
        $oldImages = $input['old_images']??[];
        $images = $input['images']??[];
        $images = $images + $oldImages;
        ksort($images);
        $input['origin_lang'] = $textLocale;
        $input['status'] = 'pending';
        $input['is_tba'] = $input['is_tba']??false;
        $input['slug'] = [
            $textLocale => makeSlug($input['title'], Post::allSlugs($post->id))
        ];
        $oldTranslations = [
            'title' => $post->translated('title', $textLocale),
            'description' => $post->translated('description', $textLocale),
            'slug' => $post->translated('slug', $textLocale),
        ];
        $input['title'] = [
            $textLocale => $input['title']
        ];
        $input['description'] = [
            $textLocale => $input['description']
        ];

        // dlog(" PostController@update. input", $input); //! LOG

        $post->update($input);
        $post->saveCosts($input);
        $post->saveTranslations($input);
        $post->images()->whereIn('id', $request->removed_images??[])->delete();
        $post->documents()->whereIn('id', $request->removed_documents??[])->delete();
        $images = $post->addAttachment(array_reverse($images), 'images', true);
        $post->addAttachment($input['documents']??[], 'documents');

        PostTranslate::dispatch($post, $oldTranslations);
        ProcessPostImages::dispatch($images);

        flash(trans('messages.posts.updated'));

        // dlog(" PostController@update. END"); //! LOG

        return $this->jsonSuccess('', [
            'redirect' => route('posts.show', $post)
        ]);
    }

    public function addToFav(Request $request, Post $post)
    {
        $user = auth()->user();
        if (!$user) {
            return $this->jsonError(trans('messages.authError'));
        }
        if ($user->id == $post->user_id) {
            return $this->jsonError(trans('messages.postAddFavPersonal'));
        }
        if ($user->favorites()->where('posts.id', $post->id)->count()) {
            $user->favorites()->detach($post);
            return $this->jsonSuccess(trans('messages.postRemovedFav'));
        }

        $user->favorites()->attach($post);
        return $this->jsonSuccess(trans('messages.postAddedFav'));
    }

    public function view(Request $request, Post $post)
    {
        $post->saveView();
    }

    public function toggle(Request $request, Post $post)
    {
        $user = auth()->user();

        abort_if($post->user_id != $user->id, 403);

        if ($post->is_active) {
            $post->is_active = false;
            $message = 'Post deactivated';//! TRANSLATE
        } else {
            if ($this->reachedPostsLimit($user)) {
                return $this->subscriptionErrorResponse(2);
            }
            $post->is_active = true;
            $message = 'Post activated';//! TRANSLATE
        }

        $post->update();

        return $this->jsonSuccess($message);
    }

    public function views(Request $request, Post $post)
    {
        $views = $post->views()->latest('updated_at')->get();

        return $this->jsonSuccess('', view('components.views', compact('views'))->render());
    }

    public function priceRequest(Request $request, Post $post)
    {
        $input = $request->validate([
            'message' => ['required', 'string', new \App\Rules\EscapedText()]
        ]);

        $from = auth()->user();

        $cKey = "price-request-from-$from->id-to-post-$post->id";
        // RateLimiter::clear($cKey);
        $executed = RateLimiter::attempt($cKey, 1, fn()=>true, 60*60*24);
        if (!$executed) {
            $avIn = ceil(RateLimiter::availableIn($cKey) / 60);

            activity('users')
                ->event('spam-price-request-for-same-post')
                ->withProperties(infoForActivityLog())
                ->on($post)
                ->log('');

            return $this->jsonError("Price request for this post has already been sent within 24 hours. Next price request in: $avIn minutes");
        }

        $cKey = "price-request-from-$from->id-to-user-$post->user_id";
        // RateLimiter::clear($cKey);
        $executed = RateLimiter::attempt($cKey, 5, fn() => true, 60*60*1);
        if (!$executed) {
            $avIn = ceil(RateLimiter::availableIn($cKey) / 60);

            activity('users')
                ->event('spam-price-request-for-same-author')
                ->withProperties(infoForActivityLog())
                ->on($post)
                ->log('');

            return $this->jsonError("Five price requests for this author has already been sent within 1 hour. Next price request in: $avIn minutes");
        }

        $from = auth()->user();
        $author = $post->user;

        if ($from->id == $author->id) {
            return $this->jsonError(trans('messages.tba.canSendToSelf'));
        }

        $emails = $author->getEmails();
        $mail = Mail::to($emails[0]);
        array_shift($emails);

        foreach ($emails as $e) {
            $mail->cc($e);
        }

        if (!$author->info->is_registered) {
            if (Setting::get('non_reg_send_price_req', true, true)) {
                $mail->send(new \App\Mail\PostTbaForNonReg($post, $from, $input['message']));
            }
        } else {
            $mail->send(new \App\Mail\PostTba($post, $from, $input['message']));
        }

        Notification::make($author->id, NotificationGroup::PRICE_REQ_RECIEVED, [
            'vars' => [
                'userName' => $from->name,
                'userEmail' => $from->getEmails(0),
                'postTitle' => $post->title
            ]
        ], $post);

        activity('posts')
            ->event('price-request')
            ->withProperties(infoForActivityLog())
            ->on($post)
            ->log('');

        return $this->jsonSuccess(trans('messages.tba.send'));
    }

    public function recover(Request $request, Post $post)
    {
        $user = auth()->user();

        abort_if($post->user_id != $user->id, 403);

        if ($post->is_active && $this->reachedPostsLimit($user)) {
            $post->is_active = false;
            $post->save();
        }

        $post->update([
            'is_trashed' => false
        ]);

        return $this->jsonSuccess('Post recovered'); //! TRANSLATE
    }

    public function trash(Request $request, Post $post)
    {
        $user = auth()->user();

        abort_if($post->user_id != $user->id, 403);

        $post->update([
            'is_trashed' => true
        ]);

        return $this->jsonSuccess('Post trashed'); //! TRANSLATE
    }

    public function destroy(Request $request, Post $post)
    {
        $user = auth()->user();

        abort_if($post->user_id != $user->id, 403);

        $post->delete();


        return $this->jsonSuccess('Post deleted'); //! TRANSLATE
    }
}
