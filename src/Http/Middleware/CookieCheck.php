<?php

namespace Elfcms\Elfcms\Http\Middleware;

use Elfcms\Elfcms\Models\TmpUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CookieCheck
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
        $tmpUserCookie = Cookie::get('tmp_user');
        if ($tmpUserCookie) {
            if (Auth::check()) {
                $user = Auth::user();
                $user->tmp = $tmpUserCookie;
                if ($user->save()) {
                    Cookie::queue(Cookie::forget('tmp_user'));
                }
            }
            else {
                try {
                    $tmpUserDb = TmpUser::where('uuid',$tmpUserCookie)->first();
                    if (empty($tmpUserDb)) {
                        TmpUser::create(['uuid'=>$tmpUserCookie]);
                    }
                }
                catch (\Exception $e) {
                    //
                }
            }
        }
        else {
            if (!Auth::check())  {
                $tmpUserUuid = (string) \Illuminate\Support\Str::uuid();
                if ($tmpUserUuid) {
                    Cookie::queue('tmp_user', $tmpUserUuid);

                    try {
                        TmpUser::create(['uuid'=>$tmpUserUuid]);
                    }
                    catch (\Exception $e) {
                        //
                    }
                }
            }
        }
        return $next($request);
    }
}
