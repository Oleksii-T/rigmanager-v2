<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\Attachment;
use App\Models\Feedback;
use App\Models\Category;
use App\Models\Setting;
use App\Http\Requests\PostRequest;
use App\Jobs\PostTranslate;
use App\Jobs\MailerProcessNewPost;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function show(Request $request, Post $post)
    {
        // return new \App\Mail\PostTba($post, auth()->user());
        $user = auth()->user();

        if (!$post->is_active && $post->user_id != $user->id) {
            return view('posts.inactive', compact('post'));
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
        ];

        return view('posts.show', $data);
    }

    public function create()
    {
        $categories = Category::all();
        list($categsFirstLevel, $categsSecondLevel, $categsThirdLevel) = Category::getLevels();

        return view('posts.create', compact('categsFirstLevel', 'categsSecondLevel', 'categsThirdLevel', 'categories'));
    }

    public function store(PostRequest $request, TranslationService $translator)
    {
        $user = auth()->user();
        $input = $request->validated();
        $textLocale = $translator->detectLanguage($input['title'] . '. ' . $input['description']);
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
        $post->saveCosts($input);
        $post->saveTranslations($input);
        $post->addAttachment(array_reverse($input['images']??[]), 'images', true);
        $post->addAttachment($input['documents']??[], 'documents');

        $chain = [new PostTranslate($post)];
        $hidePending = Setting::get('hide_pending_posts', true, true);

        if (!$hidePending) {
            $chain[] = new MailerProcessNewPost($post);
        }

        Bus::chain($chain)->dispatch();

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
        list($categsFirstLevel, $categsSecondLevel, $categsThirdLevel) = Category::getLevels();
        $activeLevels = array_column($post->category->parents(true), 'id');

        return view('posts.edit', compact('post', 'categsFirstLevel', 'categsSecondLevel', 'categsThirdLevel', 'activeLevels'));
    }

    public function update(PostRequest $request, Post $post, TranslationService $translator)
    {
        // $c = Category::find($request->category_id);
        // return $this->jsonError("Selected category: " . $c->name);

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
        $input['is_active']= true;
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
        $post->addAttachment(array_reverse($images), 'images', true);
        $post->addAttachment($input['documents']??[], 'documents');
        PostTranslate::dispatch($post, $oldTranslations);

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

    public function contacts($post)
    {
        $user = auth()->user();
        if (!$user) {
            return $this->jsonError(trans('messages.authError'));
        }

        return $this->jsonSuccess('', [
            'phone' => $post->user->phone ?? trans('ui.notSpecified'),
            'email' => $post->user->email ?? trans('ui.notSpecified')
        ]);
    }

    public function view(Request $request, Post $post)
    {
        $user = auth()->user();

        if ($user && $user->id == $post->user_id) {
            return;
        }

        $ip = $request->ip();
        $view = $post->views()->whereJsonContains('ips', $ip)->first();

        if ($view && !$view->user_id && $user) {
            $view->update([
                'user_id' => $user->id
            ]);
        }

        if (!$view && $user) {
            $view = $post->views()->where('user_id', $user->id)->first();
        }

        if ($view) {
            $view->increment('count');
            return;
        }

        $post->views()->create([
            'user_id' => $user->id??null,
            'ips' => [$ip]
        ]);

        return;
    }

    public function toggle(Request $request, Post $post)
    {
        $user = auth()->user();

        abort_if($post->user_id != $user->id, 403);

        if ($post->is_active) {
            $post->is_active = false;
            $message = 'Post deactivated';//! TRANSLATE
        } else {
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

    public function tba(Request $request, Post $post)
    {
        $from = auth()->user();
        $author = $post->user;

        if ($from->id == $author->id) {
            return $this->jsonError(trans('messages.tba.canSendToSelf'));
        }

        Mail::to($post->user->email)->send(new \App\Mail\PostTba($post, $from));

        \Log::info('TBA request sended', [
            'post_id' => $post->id,
            'post_title' => $post->title,
            'author_id' => $author->id,
            'author_email' => $author->email,
            'from_id' => $from->id,
            'from_email' => $from->email
        ]);

        return $this->jsonSuccess(trans('messages.tba.send'));
    }

    public function destroy(Request $request, Post $post)
    {
        $user = auth()->user();

        abort_if($post->user_id != $user->id, 403);

        $post->delete();


        return $this->jsonSuccess('Post deleted'); //! TRANSLATE
    }
}
