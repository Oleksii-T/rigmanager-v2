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
            ->equipment()
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

    public function autocomplete(Request $request, $type)
    {
        $search = $request->search;
        $searchLen = strlen($search);

        if ($searchLen > 15) {
            return []; // do not suggest for long search strings
        }

        if ($type == 'my-posts') {
            $column = 'title';
        } else if ($type == 'favorites') {
            $column = 'title';
        } else {
            $column = $type;
        }

        $cKey = "autocomplete-search-for-$type-$search";
        // cache()->forget($cKey); //! dev
        $result = cache()->get($cKey, null);

        if ($result !== null) {
            return $result;
        }

        // get post titles where search string found
        $posts = Post::query()
            ->visible()
            ->when($type == 'my-posts', fn ($q) => $q->where('user_id', auth()->id()))
            ->when($type == 'favorites', fn ($q) => $q->whereRelation('favoriteBy', 'user_id', auth()->id()))
            ->when($column=='title', fn ($q) => $q->whereHas('translations', function ($q1) use ($search) {
                $q1->where('field', 'title');
                $q1->where('locale', LaravelLocalization::getCurrentLocale());
                $q1->where('value', 'like', "%$search%");
            }))
            ->when($column=='manufacturer', fn ($q) => $q->where('manufacturer', 'like', "%$search%"))
            ->latest()
            ->select('id', 'manufacturer')
            ->get();

        $posts = $posts->map(fn($model) => $model->$column)->toArray();

        $result = $this->formatAutocomplete($posts, $search);

        cache()->put($cKey, $result, 60*5);

        return $result;
    }

    private function formatAutocomplete($strings, $search, $maxResultCount=10)
    {
        $searchLen = strlen($search);
        $endWordChars = [' ', '-', ',', '.', '{', '}', '(', ')'];
        $result = [];;

        // get two words from post titles with search string
        foreach ($strings as $autocompleteString) {
            $pos = strripos($autocompleteString, $search);

            // find start of the search string
            $startOfAutocompleteString = 0;
            for ($i=$pos; $i >= 0; $i--) {
                if (in_array($autocompleteString[$i], $endWordChars)) {
                    $startOfAutocompleteString = $i+1;
                    break;
                }
            }

            // find two consecutive words after search string (max 30 chars)
            $endOfAutocompleteString = $startOfAutocompleteString;
            $wordsToFind = 2 + substr_count($search, ' ');
            $wordsFound = 0;
            $maxLengthOfAutocomplete = min($startOfAutocompleteString+$searchLen+30, strlen($autocompleteString));
            $prevCharIsEndWord = false;

            for ($i=$startOfAutocompleteString; $i < $maxLengthOfAutocomplete; $i++) {
                $endOfAutocompleteString++;

                if (!in_array($autocompleteString[$i], $endWordChars)) {
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

            $autocompleteString = substr($autocompleteString, $startOfAutocompleteString, $lengthOfAutocompleteString);
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

        return $result;
    }
}
