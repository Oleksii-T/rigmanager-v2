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
            $resources = Attachment::distinct('attachmentable_type')->pluck('attachmentable_type');

            return view('admin.attachments.index', compact('resources'));
        }

        $attachments = Attachment::query()
            ->when($request->attachmentable_type, fn ($q) => $q->where('attachmentable_type', $request->attachmentable_type));

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

    public function update(Request $request, Attachment $attachment)
    {
        $data = $request->validate([
            'original_name' => ['required', 'string'],
            'alt' => ['nullable', 'string']
        ]);

        $attachment->update($data);

        return $this->jsonSuccess('Attachment updated successfully');
    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $attachment->delete();

        return $this->jsonSuccess('Attachment updated successfully');
    }
}
