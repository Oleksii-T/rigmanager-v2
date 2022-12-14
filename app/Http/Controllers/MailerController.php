<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MailerRequest;
use App\Models\Mailer;
use App\Models\User;
use App\Models\Category;

class MailerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mailers = $user->mailers()->latest()->get();

        return view('mailers.index', compact('mailers'));
    }

    public function store(MailerRequest $request)
    {
        $input = $request->validated();
        $user = auth()->user();
        $author = $input['filters']['author']??null;
        $category = $input['filters']['category']??null;

        if ($author) {
            $input['title'] = trans('messages.mailers.title.from-author') . User::find($author)->name; //! TRANSLATE
        } else if ($category)  {
            $input['title'] = Category::find($category)->name;
        } else {
            $input['title'] = trans('messages.mailers.default-title') . $user->mailers()->count() + 1; //! TRANSLATE
        }

        $input['slug'] = makeSlug($input['title'], Mailer::pluck('slug')->toArray());
        $user->mailers()->create($input);

        return $this->jsonSuccess(trans('messages.mailers.created')); //! TRANSLATE
    }

    public function edit(Mailer $mailer)
    {
        return view('mailers.edit', compact('mailer'));
    }

    public function update(MailerRequest $request, Mailer $mailer)
    {
        $input = $request->validated();
        $input['slug'] = makeSlug($input['title'], Mailer::where('id', '!=', $mailer->id)->pluck('slug')->toArray());
        $mailer->update($input);

        flash(trans('messages.mailers.updated')); //! TRANSLATE

        return $this->jsonSuccess('', [
            'redirect' => route('mailers.index')
        ]);
    }

    public function deactivate()
    {
        $user = auth()->user();
        foreach ($user->mailers as $m) {
            $m->update([
                'is_active' => false
            ]);
        }

        flash(trans('messages.mailersDeactivated'));

        return $this->jsonSuccess('', [
            'redirect' => route('mailers.index')
        ]);
    }

    public function toggle(Mailer $mailer)
    {
        $user = auth()->user();

        if ($mailer->is_active) {
            $m = trans('messages.mailersDeactivated');
        } else {
            $m = trans('messages.mailersActivated'); //! TRANSLATE
        }

        $mailer->update([
            'is_active' => !$mailer->is_active
        ]);


        flash($m);

        return $this->jsonSuccess('', [
            'redirect' => route('mailers.index')
        ]);
    }

    public function destroyAll()
    {
        $user = auth()->user();

        foreach ($user->mailers as $m) {
            $m->delete();
        }

        flash(trans('messages.mailersDeleted'));

        return $this->jsonSuccess('', [
            'redirect' => route('mailers.index')
        ]);
    }

    public function destroy(Mailer $mailer)
    {
        $user = auth()->user();

        abort_if($mailer->user_id != $user->id, 403);

        $mailer->delete();

        flash(trans('messages.mailerDeleted'));

        return $this->jsonSuccess('', [
            'redirect' => route('mailers.index')
        ]);
    }
}
