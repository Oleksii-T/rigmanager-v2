<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfilePasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\Category;

class ProfileController extends Controller
{
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
        $user->info()->update($input['info']);
        $user->addAttachment($input['avatar']??null, 'avatar');
        $user->addAttachment($input['banner']??null, 'banner');

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
            case 'delete':
                $message = "$count posts deleted";//! TRANSLATE
                foreach ($query->get() as $p) {
                    $p->delete();
                }
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
            return view('profile.posts', compact('category'));
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
        return view('profile.subscription');
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
