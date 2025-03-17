<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Closure;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/*') || $request->is('elfcms/*')) {
            $adminLocale = Setting::where('code', 'admin_locale')->value('value') ?? config('app.locale');
            app()->setLocale($adminLocale);
        } else {
            $siteLocale = Setting::where('code', 'site_locale')->value('value') ?? config('app.locale');
            app()->setLocale($siteLocale);
        }
        return $next($request);
    }
}
