<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function index()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy(Request $request, Attachment $attachment)
    {
        $attachment->delete();

        return $this->jsonSuccess('Blog updated successfully');
    }
}
