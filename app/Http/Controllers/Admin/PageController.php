<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBlock;
use App\Http\Requests\Admin\PageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.pages.index');
        }

        return Page::dataTable(Page::query());
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(PageRequest $request)
    {
        $input = $request->validated();
        Page::create($input);

        return $this->jsonSuccess('Page created successfully', [
            'redirect' => route('admin.pages.index')
        ]);
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $input = $request->validated();
        $page->update($input);

        return $this->jsonSuccess('Page updated successfully');
    }

    public function editBlocks(Page $page)
    {
        $itemGroups = $page->items()->latest()->get()->groupBy('group');

        return view('admin.pages.blocks', compact('page', 'itemGroups'));
    }

    public function updateBlocks(Request $request, Page $page)
    {
        foreach ($request->blocks as $id => $block) {
            PageBlock::findOrFail($id)->update([
                'data' => $block
            ]);
        }

        return $this->jsonSuccess('Page updated successfully', [
            'redirect' => route('admin.pages.edit', $page)
        ]);
    }

    public function destroy(Page $page)
    {
        if ($page->isStatic()) {
            return $this->jsonError('Can not delete static page', [], 200);
        }

        $page->delete();

        return $this->jsonSuccess('Page deleted successfully');
    }
}