<?php

namespace Elfcms\Elfcms\Http\Controllers;

use Elfcms\Elfcms\Models\Setting;
use Illuminate\Http\Request;

class MaintenanceController extends \App\Http\Controllers\Controller
{
    public function __invoke()
    {
        $text = Setting::value('site_maintenance_text') ?? __('elfcms::default.site_under_construction');
        return view('maintenance',[
            'page' => [
                'title' => 'Test page',
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'text' => $text
        ]);
    }
}
