<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mailer;
use App\Http\Requests\Admin\MailerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MailerController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.mailers.index');
        }

        $mailers = Mailer::query();

        if ($request->role !== null) {
            $mailers->whereHas('roles', function($q) use ($request){
                $q->where('roles.id', $request->role);
            });
        }

        return Mailer::dataTable($mailers);
    }

    public function create()
    {
        return view('admin.mailers.create');
    }

    public function store(MailerRequest $request)
    {
        $input = $request->validated();
        $mailer = Mailer::create($input);

        return $this->jsonSuccess('Mailer created successfully', [
            'redirect' => route('admin.mailers.index')
        ]);
    }

    public function edit(Mailer $mailer)
    {
        return view('admin.mailers.edit', compact('mailer'));
    }

    public function update(MailerRequest $request, Mailer $mailer)
    {
        $input = $request->validated();
        $input['is_active'] = $input['is_active'] ?? false;
        $mailer->update($input);

        return $this->jsonSuccess('Mailer updated successfully');
    }

    public function destroy(Mailer $mailer)
    {
        if ($mailer->id == auth()->id()) {
            return $this->jsonError('Can not delete current mailer', [], 200);
        }

        $mailer->delete();

        return $this->jsonSuccess('Mailer deleted successfully');
    }
}
