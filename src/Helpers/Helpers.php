<?php

use Elfcms\Elfcms\Aux\Fragment;
use Elfcms\Elfcms\Models\ElfcmsContact;
use Elfcms\Elfcms\Models\Setting;

if (!function_exists('settings')) {

    function settings()
    {
        return Setting::values();
    }
}

if (!function_exists('setting')) {

    function setting(string $code)
    {
        return Setting::value($code);
    }
}

if (!function_exists('contacts')) {

    function contacts()
    {
        return ElfcmsContact::values();
    }
}

if (!function_exists('contact')) {

    function contact(string $code)
    {
        return ElfcmsContact::value($code);
    }
}

if (!function_exists('fragment')) {

    function fragment(string $code)
    {
        return Fragment::get($code);
    }
}
