<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Request $request, Attachment $attachment)
    {
        $disk = Attachment::disk($attachment->type);

        return Storage::disk($disk)->download($attachment->name, $attachment->original_name);
    }
}
