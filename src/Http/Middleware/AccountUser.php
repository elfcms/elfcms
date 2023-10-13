<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AccountUser
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
        $routeName = Route::currentRouteName();
        if (Auth::check()) {
            if ($routeName == 'account.login' || $routeName == 'account.register' || $routeName == 'account.confirm') {
                return redirect()->guest(route('account.index'));
            }
            return $next($request);
        }

        if ($routeName != 'account.login' && $routeName != 'account.register' && $routeName != 'account.confirm' && $routeName != 'account.confirmation' && $routeName != 'account.getrestore' && $routeName != 'account.setrestore') {
            return redirect()->guest(route('account.login'));
        }

        return $next($request);
    }
}
