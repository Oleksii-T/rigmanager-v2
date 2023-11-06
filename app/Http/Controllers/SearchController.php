<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SearchController extends Controller
{
    public function index(Request $request, $slug1=null, $slug2=null, $slug3=null)
    {
        $category = $slug3 ?? $slug2 ?? $slug1;
        $filters = $request->all();

        if ($category) {
            $category = Category::getBySlug($category);
            abort_if(!$category, 404);
            $filters['category'] = $category;
        }

        if ($filters['author']??null) {
            $filters['author_name'] = User::where('slug', $filters['author'])->value('name');
        }

        if (!$request->ajax()) {
            return view('search', compact('category', 'filters'));
        }

        $posts = Post::query()
            ->visible()
            ->withCount('views')
            ->filter($filters)
            ->paginate(Post::PER_PAGE);

        $categories = $category
            ? $category->childs()->active()->get()
            : Category::active()->whereNull('category_id')->get();

        $postView = $posts->count()
            ? view('components.search.items', ['posts' => $posts])->render()
            : view('components.search.empty-result')->render();

        return $this->jsonSuccess('', [
            'posts' => $postView,
            'categories' => view('components.search.categories', compact('categories', 'filters'))->render(),
            'total' => $posts->total()
        ]);
    }

    public function autocomplete(Request $request)
    {
        $maxResultCount = 10;
        $search = $request->search;
        $searchLen = strlen($search);

        if ($searchLen > 15) {
            return []; // do not suggest for long search strings
        }

        $cKey = "autocomplete-search-for-$search";
        // cache()->forget($cKey); //! dev
        $result = cache()->get($cKey, null);

        if ($result !== null) {
            return $result;
        }

        $endWordChars = [' ', '-', ',', '.', '{', '}', '(', ')'];
        $result = [];

        // get post titles where search string found
        $postTitles = Post::query()
            ->visible()
            ->whereHas('translations', function ($q) use ($search) {
                $q->where('field', 'title');
                $q->where('locale', LaravelLocalization::getCurrentLocale());
                $q->where('value', 'like', "%$search%");
            })
            ->latest()
            ->select('id')
            ->get()
            ->map(fn($post) => $post->title)
            ->toArray();

        // get two words from post titles with search string
        foreach ($postTitles as $title) {
            $pos = strripos($title, $search);

            // find start of the search string
            $startOfAutocompleteString = 0;
            for ($i=$pos; $i >= 0; $i--) {
                if (in_array($title[$i], $endWordChars)) {
                    $startOfAutocompleteString = $i+1;
                    break;
                }
            }

            // find two consecutive words after search string (max 30 chars)
            $endOfAutocompleteString = $startOfAutocompleteString;
            $wordsToFind = 2 + substr_count($search, ' ');
            $wordsFound = 0;
            $maxLengthOfAutocomplete = min($startOfAutocompleteString+$searchLen+30, strlen($title));
            $prevCharIsEndWord = false;

            for ($i=$startOfAutocompleteString; $i < $maxLengthOfAutocomplete; $i++) {
                $endOfAutocompleteString++;

                if (!in_array($title[$i], $endWordChars)) {
                    $prevCharIsEndWord = false;
                    continue;
                }

                if (!$prevCharIsEndWord) {
                    $wordsFound++; // count word only if previous char was non 'end word' char
                }

                $prevCharIsEndWord = true;

                if ($wordsFound >= $wordsToFind) {
                    break;
                }
            }

            $lengthOfAutocompleteString = $endOfAutocompleteString-$startOfAutocompleteString;

            if ($lengthOfAutocompleteString <= $searchLen) {
                continue; // skip if autocomplete string somehow smaller than search string
            }

            $autocompleteString = substr($title, $startOfAutocompleteString, $lengthOfAutocompleteString);
            $autocompleteString = trim($autocompleteString);
            $result[] = $autocompleteString;
        }

        // sort result based on number of occurances
        $occurances = array_count_values($result);
        usort($result, function ($a, $b) use ($occurances) {
            return $occurances[$b] - $occurances[$a];
        });

        // make result unique
        $result = array_iunique($result);
        $result = array_slice($result, 0, $maxResultCount);
        $result = array_values($result);

        cache()->put($cKey, $result, 60*5);

        return $result;
    }
}
