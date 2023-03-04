<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mailer;
use App\Models\MailerLog;
use App\Models\Post;
use Illuminate\Http\Request;

class MailerLogController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.mailer-logs.index');
        }

        $logs = MailerLog::query();

        if ($request->mailer !== null) {
            $logs->where('mailer_id', $request->mailer);
        }

        return MailerLog::dataTable($logs);
    }
}
