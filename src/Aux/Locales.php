<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\Setting;

class Locales
{

    protected static $settingCode = 'site_locale';
    protected static $adminCode = 'admin_locale';

    public static function getSetting($full = false)
    {
        $result = Setting::where('code', self::$settingCode)->first();
        if ($full === true) {
            return $result;
        }
        return $result->value;
    }

    public static function get()
    {
        $locales = config('elfcms.elfcms.locales');
        if ($locales) {
            $siteLocale = self::getSetting();
            if ($siteLocale && self::isset($siteLocale)) {
                return $siteLocale;
            }
        }
        return config('app.locale');
    }

    public static function all()
    {
        return config('elfcms.elfcms.locales');
    }

    public static function set()
    {
        return config(['app.locale' => self::get()]);
    }

    public static function isset(String $code)
    {
        $locales = config('elfcms.elfcms.locales');
        if ($locales) {
            foreach ($locales as $locale) {
                if ($code == $locale['code']) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function setSetting(String $code)
    {
        if (self::isset($code)) {
            $setting = Setting::where('code', self::$settingCode)->first();
            $setting->value = $code;
            return $setting->save();
        }

        return false;
    }

    public static function setAdminLocale(String $code)
    {
        if (self::isset($code)) {
            $setting = Setting::where('code', self::$adminCode)->first();
            $setting->value = $code;
            return $setting->save();
        }

        return false;
    }
}
