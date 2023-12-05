<?php

namespace Elfcms\Elfcms\Http\Controllers\Publics;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Elfcms\Elfcms\Aux\TextPrepare;
use Elfcms\Elfcms\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public static function get(Page $page, $dynamic = true)
    {
        if ($dynamic === true && $page->is_dynamic != 1) {
            abort(404);
        }
        return view('elfcms::public.pages.default',[
            'page' => [
                'title' => $page->title,
                'current' => url()->current(),
                'keywords' => $page->meta_keywords,
                'description' => $page->meta_description
            ],
            'pageData' => $page
        ]);
    }

    public function about()
    {
        $page = Page::where('slug','about')->first();
        return view('elfcms::public.pages.default',[
            'page' => [
                'title' => $page->title,
                'current' => url()->current(),
                'keywords' => $page->meta_keywords,
                'description' => $page->meta_description
            ],
            'pageData' => $page
        ]);
    }

}
