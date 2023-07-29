<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Import;
use App\Models\Post;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.imports.index');
        }

        $imports = Import::query();

        return Import::dataTable($imports);
    }

    public function show(Request $request, Import $import)
    {
        if (!$request->ajax()) {
            return view('admin.imports.show', compact('import'));
        }

        $posts = Post::whereIn('id', $import->posts);

        return Post::dataTable($posts);
    }
}
