<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index(Request $request)
    {
        $configs = config('elfcms');
        $modules = [];
        if (is_array($configs) && count($configs) > 1) {
            $modules = $configs;
            if (!empty($modules['elfcms'])) unset($modules['elfcms']);
        }
        $data = $configs['elfcms'];
        return view('elfcms::admin.system.index',[
            'page' => [
                'title' => __('elfcms::default.system'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'data' => $data,
            'modules' => $modules,
        ]);
    }
}
