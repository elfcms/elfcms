<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\CookieSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class CookieConsentController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::debug(var_export($request->all(), true));
        $result = [];
        $result['consent'] = boolval($request->consent) ?? false;

        if (!empty($request->categories)) {
            $result['categories'] = $request->categories;
        }

        $settings = CookieSetting::first();
        $lifetime = $settings->cookie_lifetime ?? 525600;

        Cookie::queue(Cookie::make('cookie_consent',json_encode($result), $lifetime));
    }
}
