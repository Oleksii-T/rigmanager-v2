<?php

namespace App\Http\Controllers;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Http\Request;
use App\Http\Requests\ImportRequest;
use App\Actions\ValidatePostsImport;
use App\Jobs\PostsImport as PostsImportJob;
use App\Models\Import;
use App\Models\Post;

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
        return view('imports.create');
    }

    public function store(ImportRequest $request)
    {
        $file = $request->file('file');
        $user = auth()->user();

        [$errorRow, $errorMessage] = ValidatePostsImport::run($file);

        if ($errorMessage) {
            if ($errorRow) {
                $errorMessage = trans('messages.import.errors.AtPost') . "$errorRow: $errorMessage";
            }
            return $this->jsonError($errorMessage);
        }

        $import = $user->imports()->create([
            'status' => Import::STATUS_PENDING
        ]);
        $import->addAttachment($file);

        PostsImportJob::dispatch($import);

        flash(trans('messages.imports.import-started'));

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

        Post::whereIn('id', $import->posts)->delete();
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
