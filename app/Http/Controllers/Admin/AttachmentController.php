<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.attachments.index');
        }

        $attachments = Attachment::query();

        if ($request->role !== null) {
            $attachments->whereHas('roles', function($q) use ($request){
                $q->where('roles.id', $request->role);
            });
        }

        return Attachment::dataTable($attachments);
    }

    public function edit(Attachment $attachment)
    {
        $resizes = Attachment::getAllResize();
        $resized = [];
        foreach ($resizes as $w => $h) {
            $path = $attachment->compressed($w, $h, true);
            $url = $attachment->compressed($w, $h);
            if (file_exists($path)) {
                $resized[] = [
                    'url' => $url,
                    'path' => $path
                ];
            }
        }

        return view('admin.attachments.edit', compact('attachment', 'resized'));
    }

    public function update(AttachmentRequest $request, Attachment $attachment)
    {
        //TODO
    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $attachment->delete();

        return $this->jsonSuccess('Attachment updated successfully');
    }
}
