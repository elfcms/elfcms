<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Closure;
use Elfcms\Elfcms\Models\Setting;
use Elfcms\Elfcms\Models\VisitStatistic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class VisitStatistics
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
        if (Schema::hasTable('elfcms_settings')) {
            $useStatistic = Setting::value('site_statistics_use');
            if (empty($useStatistic) || $useStatistic != 1) {
                return $next($request);
            }
        }

        $currentRouteName = Route::currentRouteName();
        if (Str::startsWith($currentRouteName,'admin') || Str::startsWith($currentRouteName,'first-') || Str::startsWith($currentRouteName,'form-')) {
            return $next($request);
        }

        $browsers = ['IE','Internet Explorer','Edge','Chrome','Firefox','Safari','Vivaldi','Opera'];

        $data['user_id'] = null;
        $data['tmp_user_uuid'] = null;
        $data['uri'] = $_SERVER['REQUEST_URI'] ?? null;
        $data['ip'] = $_SERVER['REMOTE_ADDR'] ?? null;
        $data['agent'] = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $data['referer'] = $_SERVER['HTTP_REFERER'] ?? null;
        $data['browser'] = null;
        $data['browser_full'] = null;
        if (!empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM'])) {
            $data['platform'] = str_replace('"','',$_SERVER['HTTP_SEC_CH_UA_PLATFORM']) ?? null;
        }
        if (!empty($_SERVER['HTTP_SEC_CH_UA_MOBILE'])) {
            $data['mobile'] = intval(str_replace('?','',$_SERVER['HTTP_SEC_CH_UA_MOBILE'])) ?? null;
        }
        if (!empty($_SERVER['HTTP_SEC_CH_UA'])) {
            $data['browser_full'] = $_SERVER['HTTP_SEC_CH_UA'] ?? null;
            foreach ($browsers as $browser) {
                if (mb_strpos($_SERVER['HTTP_SEC_CH_UA'],$browser) !== false) {
                    $data['browser'] = $browser;
                    break;
                }
            }
        }
        if (empty($data['browser']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
            foreach ($browsers as $browser) {
                if (mb_strpos($_SERVER['HTTP_USER_AGENT'],$browser) !== false) {
                    $data['browser'] = $browser;
                    break;
                }
            }
        }
        if(!($data['method'] = $request->method())) {
            $data['method'] = null;
        }

        //$data['full_data'] = json_encode($_SERVER) ?? null;

        if (Auth::check()) {
            $user = Auth::user();
            $data['user_id'] = $user->id;
            $data['tmp_user_uuid'] = $user->tmp;
        }
        else {
            $data['tmp_user_uuid'] = Cookie::get('tmp_user');
        }
        VisitStatistic::create($data);

        return $next($request);
    }
}
