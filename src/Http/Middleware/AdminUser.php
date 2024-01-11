<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminUser
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
        if ($request->method() == 'POST') {
            return $next($request);
        }
        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
                foreach ($user->roles as $role) {
                    if ($role->code == 'admin') {
                        return $next($request);
                    }
                }
            }
        }
        $currentRoute = Route::currentRouteName();
        if (!in_array($currentRoute, ['admin.login','admin.getrestore','admin.setrestore'])) {
            return redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}
