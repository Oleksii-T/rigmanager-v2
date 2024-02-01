<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfilePasswordRequest;
use App\Actions\Fortify\PasswordValidationRules;

class ProfileController extends Controller
{
    use PasswordValidationRules;

    public function index()
    {
        $info = auth()->user()->info;

        return view('profile.index', compact('info'));
    }

    public function chat()
    {
        return view('profile.chat');
    }

    public function message(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'message' => ['required', 'string']
        ]);

        \App\Models\Message::create([
            'user_id' => auth()->id(),
            'reciever_id' => $user->id,
            'message' => $request->message
        ]);

        return $this->jsonSuccess('Message send successfully!', [
            'chat_url' => route('profile.chat') . '?chat_with=' . $user->id
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $input = $request->validated();
        $user = auth()->user();
        $user->update($input);
        $input['info']['emails'] = array_filter($input['info']['emails']);
        $input['info']['phones'] = array_filter($input['info']['phones']??[]);
        \App\Models\UserInformation::where('user_id', $user->id)->first()->update($input['info']);
        $avatar = $user->addAttachment($input['avatar']??null, 'avatar');
        $banner = $user->addAttachment($input['banner']??null, 'banner');
        \App\Jobs\ProcessUserImages::dispatch($avatar, $banner);

        return $this->jsonSuccess(trans('messages.profile.updated'));
    }

    public function credentials()
    {
        return view('profile.credentials');
    }

    public function password(ProfilePasswordRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return $this->jsonSuccess(trans('messages.profile.updated-password'));
    }

    public function login(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'email' => ['required', 'email', "unique:users,email,$user->id"]
        ]);

        $user->update([
            'email' => $data['email']
        ]);

        return $this->jsonSuccess(trans('messages.profile.updated-login'));
    }

    public function posts(Request $request, $slug1=null, $slug2=null, $slug3=null)
    {
        $category = $slug3 ?? $slug2 ?? $slug1;
        $filters = $request->all();

        if ($category) {
            $category = Category::getBySlug($category);
            abort_if(!$category, 404);
            $filters['category'] = $category;
        }

        if (!$request->ajax()) {
            return view('profile.posts', compact('category'));
        }

        $posts = auth()->user()->posts()->withCount('views')->filter($filters);
        $categories = $this->getPostsCategories($posts, $category);
        $posts = $posts->paginate(20);
        $categRoute = 'profile.posts';
        $postView = $posts->count()
            ? view('components.profile.items', ['posts' => $posts])->render()
            : view('components.search.empty-result')->render();

        return $this->jsonSuccess('', [
            'posts' => $postView,
            'categories' => view('components.profile.categories', compact('categories', 'filters', 'categRoute'))->render(),
            'total' => $posts->total()
        ]);
    }

    public function registerSimpleForm(Request $request)
    {
        $submitUrl = URL::temporarySignedRoute('profile.register-simple', now()->addHour());
        $user = \App\Models\User::query()
            ->where('email', $request->email)
            ->whereRelation('info', 'is_registered', false)
            ->firstOrFail();

        return view('auth.register-simple', compact('user', 'submitUrl'));
    }

    public function registerSimple(Request $request)
    {
        $input = $request->validate([
            'password' =>  $this->passwordRules(),
            'email' =>  ['required', 'exists:users'],
            'agreement' => ['required', 'accepted']
        ]);

        $user = \App\Models\User::query()
            ->where('email', $input['email'])
            ->whereRelation('info', 'is_registered', false)
            ->firstOrFail();

        $user->update([
            'password' => Hash::make($input['password'])
        ]);

        $user->info()->update([
            'is_registered' => true
        ]);

        auth()->login($user);

        User::informAdmins("A known user finished the registeration! User: $user->name <$user->email>");

        return $this->jsonSuccess(trans('messages.registerSuccess'), [
            'redirect' => route('index')
        ]);
    }

    public function action(Request $request)
    {
        $user = auth()->user();
        $query = $user->posts()->filter($request->filters);
        $selected = $request->selected??[];

        if ($request->all) {
            $query->whereNotIn('id', $selected);
        } else {
            if (!$selected) {
                return $this->jsonError('No posts selected');//! TRANSLATE
            }
            $query->whereIn('id', $selected);
        }

        $count = $query->count();

        switch ($request->action) {
            case 'activete':

                if ($this->reachedPostsLimit($user, $count-1)) {
                    return $this->subscriptionErrorResponse(2);
                }

                $message = "$count posts activated";//! TRANSLATE
                $query->update([
                    'is_active' => true
                ]);
                break;
            case 'deactivate':
                $message = "$count posts deactivated";//! TRANSLATE
                $query->update([
                    'is_active' => false
                ]);
                break;
            case 'trash':
                $message = "$count posts trashed";//! TRANSLATE
                $query->update([
                    'is_trashed' => true
                ]);
                break;
            case 'recover':
                $message = "$count posts recovered";//! TRANSLATE

                $data = [
                    'is_trashed' => false
                ];

                if (!$user->isSub(2)) {
                    $data['is_active'] = false;
                }

                $query->update($data);
                break;
            default:
                return $this->jsonError('Invalid action');//! TRANSLATE
        }

        return $this->jsonSuccess($message);
    }

    public function favorites(Request $request, $slug1=null, $slug2=null, $slug3=null)
    {
        $category = $slug3 ?? $slug2 ?? $slug1;
        $filters = $request->all();

        if ($category) {
            $category = Category::getBySlug($category);
            abort_if(!$category, 404);
            $filters['category'] = $category;
        }

        if (!$request->ajax()) {
            return view('profile.favorites', compact('category'));
        }

        $posts = auth()->user()->favorites()->visible()->withCount('views')->filter($filters);
        $categories = $this->getPostsCategories($posts, $category);
        $posts = $posts->paginate(20);
        $categRoute = 'profile.favorites';
        $postView = $posts->count()
            ? view('components.profile.favorites', ['posts' => $posts])->render()
            : view('components.search.empty-result')->render();

        return $this->jsonSuccess('', [
            'posts' => $postView,
            'categories' => view('components.profile.categories', compact('categories', 'filters', 'categRoute'))->render(),
            'total' => $posts->total()
        ]);
    }

    public function clearFavs(Request $request)
    {
        $user = auth()->user();
        $user->favorites()->sync([]);

        flash('Favorites cleared');//! TRANSLATE

        return $this->jsonSuccess();
    }

    public function subscription(Request $request)
    {
        $user = auth()->user();
        $sub = $user->activeSubscription();
        $cycles = $user->subscriptionCycles()->latest()->paginate(5);

        return view('profile.subscription', compact('sub', 'cycles'));
    }

    /**
     * Get categories in which user has posts (only parent categs with posts count)
     */
    private function getPostsCategories($query, $category)
    {
        $postIds = $query->pluck('posts.id')->toArray();

        $query = $category
            ? $category->childs()
            : Category::whereNull('category_id');

        $result = [];
        foreach ($query->get() as $c) {
            $count = $c->postsAll()->whereIn('id', $postIds)->count();
            if ($count) {
                $c->all_posts_count = $count;
                $result[] = $c;
            }
        }

        return $result;
    }
}
