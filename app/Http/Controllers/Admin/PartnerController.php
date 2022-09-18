<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Http\Requests\Admin\PartnerRequest;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.partners.index');
        }

        $partners = Partner::query();

        if ($request->role !== null) {
            $partners->whereHas('roles', function($q) use ($request){
                $q->where('roles.id', $request->role);
            });
        }

        return Partner::dataTable($partners);
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(PartnerRequest $request)
    {
        $input = $request->validated();
        $input['order'] = $input['order'] ?? (Partner::all()->max('order') + 1);
        $partner = Partner::create($input);
        $partner->addAttachment($input['image']);

        return $this->jsonSuccess('Partner created successfully', [
            'redirect' => route('admin.partners.index')
        ]);
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(PartnerRequest $request, Partner $partner)
    {
        $input = $request->validated();

        $partner->update($input);
        $partner->addAttachment($input['image']??null);

        return $this->jsonSuccess('Partner updated successfully');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return $this->jsonSuccess('Partner deleted successfully');
    }
}
