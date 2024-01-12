<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Closure;
use Elfcms\Elfcms\Aux\Admin\Permissions;
use Elfcms\Elfcms\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AdminAccess
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
        /* if ($request->method() == 'POST') {
            return $next($request);
        } */
        $currentRoute = Route::currentRouteName();
        //dd($currentRoute);
        //Str::startsWith
        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
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
                if ($read == 0) {
                    abort(403, __("You don't have permission to access this page"));
                }
                if (Str::endsWith($currentRoute,['edit','store','update','destroy','delete']) && $write == 0)  {
                    abort(403,__('You do not have write permissions'));
                }

            }
        }
        /* if (!in_array($currentRoute, ['admin.login','admin.getrestore','admin.setrestore'])) {
            return redirect()->guest(route('admin.login'));
        } */

        return $next($request);
    }
}
