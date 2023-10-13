<?php

namespace Elfcms\Elfcms\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{

    public static function index()
    {
        //dd($page);
        //dd(Setting::values());
        //dd($_SERVER);

        //dd(Cookie::get('tmp_user'));
        $pageData = Page::where('slug','home')->first();
        $pageContent = '';
        if (!empty($pageData->content) && !empty($pageData)) {
            $pageContent = $pageData->content;
        }
        return view('basic::public.index',[
            'page' => [
                'title' => $pageData->title,
                'current' => url()->current(),
            ],
            'pageContent' => $pageContent
        ]);
    }

    public static function contact()
    {
        $pageData = Page::where('slug','contact')->first();
        $pageContent = '';
        if (!empty($pageData->content) && !empty($pageData)) {
            $pageContent = $pageData->content;
        }
        return view('basic::public.contact',[
            'page' => [
                'title' => $pageData->title ?? null,
                'current' => url()->current(),
            ],
            'pageContent' => $pageContent
        ]);
    }

}
