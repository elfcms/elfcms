<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index(Request $request)
    {
        $configs = config('elfcms');
        $data = $configs['elfcms'];
        return view('elfcms::admin.system.index',[
            'page' => [
                'title' => __('elfcms::default.system'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'data' => $data,
        ]);
    }
}
