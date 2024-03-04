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

            if (Auth::check()) {
                $user = Auth::user();
                if ($user) {
                    $currentRoute = Route::currentRouteName();
                    $roleIDs = [];
                    foreach ($user->roles as $role) {
                        $roleIDs[] = $role->id;
                        if ($role->code == 'admin') {
                            return $next($request);
                        }
                    }
                    $accessRoutes = Permissions::routes(false);
                    $read = 0;
                    $write = 0;
                    if ($currentRoute == 'admin.index') {
                        $perms = RolePermission::whereIn('role_id',$roleIDs)->get();
                        if ($perms->sum('read') > 0 || $perms->sum('write') > 0) {
                            return $next($request);
                        }
                    }
                    foreach ($accessRoutes as $routeName => $routeData) {
                        if (Str::startsWith($currentRoute, $routeName)) {
                            $permissions = RolePermission::where('route',$routeName)->whereIn('role_id',$roleIDs)->get();
                            $read += $permissions->sum('read');
                            $write += $permissions->sum('write');
                        }
                    }
                    if ($read > 0) {
                        return $next($request);
                    }

                }
            }

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
        }
        return $next($request);
    }
}
