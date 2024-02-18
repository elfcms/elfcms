<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Closure;
use Elfcms\Elfcms\Aux\Admin\Permissions;
use Elfcms\Elfcms\Http\Controllers\MaintenanceController;
use Elfcms\Elfcms\Models\RolePermission;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isMaintenance = boolval(Setting::value('site_maintenance')) ?? false;
        if ($isMaintenance && !Str::startsWith(Route::current()->getName(), 'admin')) {
            //return redirect()->route('maintenance');
            $text = Setting::value('site_maintenance_text') ?? __('elfcms::default.site_under_construction');
        return response(view('maintenance',[
            'page' => [
                'title' => __('elfcms::default.site_under_construction'),
                'current' => url()->current(),
                'keywords' => '',
                'description' => ''
            ],
            'text' => $text
        ]));
            //return MaintenanceController::index();
        }
        return $next($request);
    }
}
