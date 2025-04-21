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
        $adminPath = trim(config('elfcms.elfcms.admin_path') ?? 'admin', '/');
        if ($request->routeIs('admin.*') || $request->is($adminPath . '/*')) {
            $adminLocale = Setting::where('code', 'admin_locale')->value('value') ?? config('app.locale');
            app()->setLocale($adminLocale);
        } else {
            $siteLocale = Setting::where('code', 'site_locale')->value('value') ?? config('app.locale');
            app()->setLocale($siteLocale);
        }
        return $next($request);
    }
}
