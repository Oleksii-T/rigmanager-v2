<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Import;
use Illuminate\Http\Request;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\PrepImportRequest;
use App\Jobs\PostsImport as PostsImportJob;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $imports = $user->imports()->latest()->get();

        return view('imports.index', compact('imports'));
    }

    public function create(Request $request)
    {
        $importColumnsValues = [
            'title' => [
                'name' => 'Title',
                'help' => '',
                'required' => 1
            ],
            'description' => [
                'name' => 'Description',
                'help' => '',
                'required' => 1
            ],
            'category' => [
                'name' => 'Category',
                'help' => 'Category name or code must be used. See details at the bottom of the page',
                'required' => 1
            ],
            'images' => [
                'name' => 'Images Links',
                'help' => 'It is possible to enter few public links to images separated by space or new line. Images will be downloaded and attached to the post',
                'required' => 0
            ],
            'type' => [
                'name' => 'Type',
                'help' => 'Possible values: sell, buy, rent, lease',
                'required' => 0
            ],
            'condition' => [
                'name' => 'Condition',
                'help' => 'Possible values: new, used, for-parts',
                'required' => 0
            ],
            'amount' => [
                'name' => 'Quantity',
                'help' => 'Quantity of items listed in post. Free format',
                'required' => 0
            ],
            'manufacturer' => [
                'name' => 'Manufacturer',
                'help' => 'Manufacturer of item listed in post. Free format',
                'required' => 0
            ],
            'manufactureDate' => [
                'name' => 'Manufacture Date',
                'help' => 'Manufacture Date of item listed in post. Free format',
                'required' => 0
            ],
            'partNumber' => [
                'name' => 'Part Number',
                'help' => 'Part Number of item listed in post. Free format',
                'required' => 0
            ],
            'cost' => [
                'name' => 'Cost',
                'help' => 'Cost of item listed in post. Possible currencies: $,¥,₴,₽,€. Format: $123567.89',
                'required' => 0
            ],
            'country' => [
                'name' => 'Country',
                'help' => 'ISO code of country of item listed in post. E.g. us',
                'required' => 0
            ],
        ];

        return view('imports.create', compact('importColumnsValues'));
    }

    public function prepareStore(PrepImportRequest $request)
    {
        $pages = \Excel::toArray(new \App\Imports\PostsImport, $request->file);
        $rows = $pages[0]; // get first excel page
        $columnNames = [];
        for ($i=0; $i<count($rows[0]); $i++) {
            $columnNames[$i] = intToAlphabet($i+1);
        }

        return $this->jsonSuccess('', [
            'total_rows' => count($rows),
            'total_columns' => count($rows[0]),
            'column_names' => $columnNames,
            'name' => $request->file->getClientOriginalName()
        ]);
    }

    public function store(ImportRequest $request)
    {
        $file = $request->file('file');
        $user = auth()->user();

        $import = $user->imports()->create([
            'settings' => [
                'start_row' => $request->start_row,
                'end_row' => $request->end_row,
                'columns' => $request->columns,
            ],
            'status' => Import::STATUS_PENDING
        ]);
        $import->addAttachment($file);

        PostsImportJob::dispatch($import);

        flash(trans('messages.import.import-started'));

        return $this->jsonSuccess('', [
            'redirect' => route('imports.index')
        ]);
    }

    public function downloadExample()
    {
        $disk = Import::getExamplesDisk();
        $currLocale = LaravelLocalization::getCurrentLocale();
        $path = $disk->exists("$currLocale.xlsx")
            ? $disk->path("$currLocale.xlsx")
            : $disk->path("en.xlsx");

        return response()->download($path, trans('messages.import.example-file-name'));
    }

    public function posts(Import $import)
    {
        abort_if(auth()->id() != $import->user_id, 403);

        $posts = Post::whereIn('id', $import->posts)->latest()->get();
        $view = view('components.import-posts', compact('posts'))->render();

        return $this->jsonSuccess('', $view);
    }

    public function download(Import $import)
    {
        abort_if(auth()->id() != $import->user_id, 403);

        return response()->download($import->file->path, $import->file->original_name);
    }

    public function postsDelete(Request $request, Import $import)
    {
        abort_if(auth()->id() != $import->user_id, 403);

        $posts = Post::whereIn('id', $import->posts)->get();

        foreach ($posts as $post) {
            $post->delete();
        }

        $import->update([
            'posts' => []
        ]);

        return $this->jsonSuccess(trans('messages.import.posts-deleted'), [
            'redirect' => route('imports.index')
        ]);
    }

    public function postsDeactivate(Request $request, Import $import)
    {
        abort_if(auth()->id() != $import->user_id, 403);

        Post::whereIn('id', $import->posts)->update([
            'is_active' => false
        ]);

        return $this->jsonSuccess(trans('messages.import.posts-deactivated'));
    }

    public function postsActivate(Request $request, Import $import)
    {
        abort_if(auth()->id() != $import->user_id, 403);

        Post::whereIn('id', $import->posts)->update([
            'is_active' => true
        ]);

        return $this->jsonSuccess(trans('messages.import.posts-activated'));
    }
}
