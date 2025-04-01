<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        //dd(backup_settings('database'));
        //backup_settings('database');
        dd(public_path());
    }
}
