<?php

use Elfcms\Elfcms\Aux\Fragment;
use Elfcms\Elfcms\Aux\Image;
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

if (!function_exists('imgCrop')) {

    function imgCrop(string $file, string $destination, int $width, int $height, array $position = ['center', 'center'])
    {
        return Image::crop($file, $destination, $width, $height, $position);
    }
}

if (!function_exists('imgCropCache')) {

    function imgCropCache(string $file, int $width, int $height, array $position = ['center', 'center'])
    {
        return Image::cropCache($file, $width, $height, $position);
    }
}

if (!function_exists('imgResize')) {

    function imgResize($filePath, $width = null, $height = null, $coef = 1, $resultFile = null, $gd = false)
    {
        return Image::adaptResize($filePath, $width, $height, $coef, $resultFile, $gd);
    }
}

if (!function_exists('imgResizeCache')) {

    function imgResizeCache($filePath, $width = null, $height = null, $coef = 1)
    {
        return Image::adaptResizeCache($filePath, $width, $height, $coef);
    }
}
