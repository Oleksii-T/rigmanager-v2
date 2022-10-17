<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\FaqRequest;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.faqs.index');
        }

        $faqs = Faq::query();

        return Faq::dataTable($faqs);
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(FaqRequest $request)
    {
        $input = $request->validated();

        $faq = Faq::create($input);
        $faq->saveTranslations($input);

        return $this->jsonSuccess('Faq updated successfully');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $input = $request->validated();

        $faq->update($input);
        $faq->saveTranslations($input);

        return $this->jsonSuccess('Faq updated successfully');
    }

    public function destroy(Request $request, Faq $faq)
    {
        $faq->delete();

        return $this->jsonSuccess('Faq deleted successfully');
    }
}
