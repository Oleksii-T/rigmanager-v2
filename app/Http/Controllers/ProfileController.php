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
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(ProfileRequest $request)
    {
        $input = $request->validated();
        $user = auth()->user();
        $user->update($input);
        $user->addAttachment($input['avatar']??null, 'avatar');

        return $this->jsonSuccess(trans('messages.profile.updated'));
    }

    public function password(ProfilePasswordRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return $this->jsonSuccess(trans('messages.profile.updated-password'));
    }

    public function posts(Request $request, Category $category=null)
    {
        $user = auth()->user();
        $query = $user->posts()->with('views')->withCount('views');
        $filters = $request->all();
        $filters['category'] = $category;
        Post::applyFilters($query, $filters);
        $categories = $this->getPostsCategories($query, $category);
        $posts = $query->paginate(20);

        if (!$request->ajax()) {
            return view('profile.posts', compact('posts', 'categories', 'category'));
        }

        return $this->jsonSuccess('', [
            'view' => view('components.profile.items', compact('posts'))->render(),
            'total' => $posts->total()
        ]);
    }

    public function action(Request $request)
    {
        $user = auth()->user();
        $query = $user->posts();
        $selected = $request->selected??[];

        Post::applyFilters($query, $request->filters);

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

    /**
     * Get categories in which user has posts (only parent categs with posts count)
     */
    private function getPostsCategories($query, $category)
    {
        $postIds = $query->pluck('id')->toArray();

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
