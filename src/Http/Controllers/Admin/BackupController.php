<?php

namespace Elfcms\Elfcms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Backup;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $backups = Backup::orderBy('created_at','desc')->get();

        dd($backups[0]);
    }
}
