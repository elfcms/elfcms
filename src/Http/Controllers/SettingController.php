<?php

namespace Elfcms\Elfcms\Http\Controllers;

use Elfcms\Elfcms\Aux\Locales;
use Elfcms\Elfcms\Http\Requests\Admin\SettingRequest;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SettingController extends \App\Http\Controllers\Controller
{
    protected $strings = [
        ['code' => 'site_name', 'name' => 'Site name', 'params' => '{}'],
        ['code' => 'site_title', 'name' => 'Site title', 'params' => '{}'],
        ['code' => 'site_logo', 'name' => 'Site logo', 'params' => '{"type": "image"}'],
        ['code' => 'site_icon', 'name' => 'Site icon', 'params' => '{"type": "image"}'],
        ['code' => 'site_slogan', 'name' => 'Site slogan', 'params' => '{}'],
        ['code' => 'site_keywords', 'name' => 'Site keywords', 'params' => '{"type": "text"}'],
        ['code' => 'site_description', 'name' => 'Site description', 'params' => '{"type": "text"}'],
        ['code' => 'site_locale', 'name' => 'Site locale', 'params' => '{"type": "list"}'],
        ['code' => 'site_statistics_use', 'name' => 'Use statistics', 'params' => '{"type": "checkbox"}', 'value' => 0],
    ];

    public function index()
    {
        $settings = Setting::all()->toArray();
        foreach ($settings as $item => $setting) {
            $settings[$item]['params'] = json_decode($setting['params'], true);
            if (Lang::has('elfcms::default.' . $setting['code'])) {
                $settings[$item]['name'] = __('elfcms::default.' . $setting['code']);
            }
        }
        $locales = Locales::all();
        return view('elfcms::admin.settings.index', [
            'page' => [
                'title' => __('elfcms::default.settings'),
                'current' => url()->current(),
            ],
            'settings' => $settings,
            'locales' => $locales
        ]);
    }

    public static function get(String $code, $full = false)
    {
        $result = Setting::where('code', $code)->first();
        if ($full === true) {
            return $result;
        }
        return $result->value;
    }

    public function save(SettingRequest $request)
    {
        $requestArray = $request->all();
        $settings = Setting::all();
        foreach ($settings as $setting) {
            $params = json_decode($setting->params, true);
            if (!empty($params) && !empty($params['type']) && $params['type'] == 'image') {
                if (!empty($request->file()[$setting->code])) {
                    $image = $request->file()[$setting->code]->store('public/elfcms/settings/site/image');
                    $setting->value = str_ireplace('public/', '/storage/', $image);
                } else {
                    $setting->value = $requestArray[$setting->code . '_path'];
                }
            } elseif (!empty($params) && !empty($params['type']) && $params['type'] == 'checkbox') {
                $setting->value = !empty($requestArray[$setting->code]) ? 1 : 0;
            } else {
                $setting->value = $requestArray[$setting->code];
            }
            //dd($setting);
            $setting->save();
        }
        //dd($request);
        return redirect(route('admin.settings.index'))->with('settingedited', 'Settings edited successfully');
    }

    public function start()
    {
        foreach ($this->strings as $string) {
            $exists = Setting::where('code', $string['code'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = Setting::create($string);

            if (!$newString) {
                return false;
            }
        }
    }
}
