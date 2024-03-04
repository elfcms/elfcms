<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\CookieCategory;
use Elfcms\Elfcms\Models\CookieSetting;
use Illuminate\Http\Request;

class CookieSettingController extends Controller
{
    public function index () {
        $settings = CookieSetting::first();
        $categories = CookieCategory::all();
        $cookie_lifetime = 365;
        if (!empty($settings->cookie_lifetime)) {
            $cookie_lifetime = (int)$settings->cookie_lifetime / 1440;
        }

        return view('elfcms::admin.cookie-settings.index', [
            'page' => [
                'title' => __('elfcms::default.cookie_settings'),
                'current' => url()->current(),
            ],
            'settings' => $settings,
            'categories' => $categories,
            'cookie_lifetime' => $cookie_lifetime,
        ]);
    }

    public function save(Request $request) {
        if (empty($request->active)) {
            $active = 0;
        } else {
            $active = 1;
        }
        if (empty($request->use_default_text)) {
            $use_default_text = 0;
        } else {
            $use_default_text = 1;
        }

        if (!empty($request->cookie_lifetime)) {
            $cookie_lifetime = intval($request->cookie_lifetime) * 1440;
        } else {
            $cookie_lifetime = 525600;
        }

        $settings = CookieSetting::first();
        if (empty($settings)) {
            CookieSetting::create([
                'active'=>$active,
                'use_default_text'=>$use_default_text,
                'text'=>$request->text ?? null,
                'privacy_path'=>$request->privacy_path ?? null,
                'cookie_lifetime'=>$cookie_lifetime  ?? null
            ]);
        }
        else {
            $settings->update([
                'active'=>$active,
                'use_default_text'=>$use_default_text,
                'text'=>$request->text ?? null,
                'privacy_path'=>$request->privacy_path ?? null,
                'cookie_lifetime'=>$cookie_lifetime  ?? null
            ]);
        }

        if (!empty($request->category)) {
            foreach($request->category as $id => $category) {
                $required = $request->category_required[$id] ?? 0;
                $remove = $request->category_remove[$id] ?? 0;
                $setting = CookieCategory::find($id);
                if ($setting) {
                    if (!empty($remove)) {
                        $setting->delete();
                    }
                    elseif ($setting->name != $category || $setting->required != $required)
                    $setting->update(['name'=>$category,'required'=>$required]);
                }
            }
        }
        if (!empty($request->category_new)) {
            foreach($request->category_new as $id => $category) {
                $required = $request->category_required[$id] ?? 0;
                $remove = $request->category_remove[$id] ?? 0;
                if (empty($remove) && !empty($category)) {
                    $newCategory = CookieCategory::create(['name'=>$category,'required'=>$required]);
                }
            }
        }

        return redirect(route('admin.cookie-settings.index'))->with('settingedited', __('elfcms::default.settings_edited_successfully'));
    }
}
