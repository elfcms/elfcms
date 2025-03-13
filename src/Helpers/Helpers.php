<?php

use Elfcms\Elfcms\Aux\Files;
use Elfcms\Elfcms\Aux\Filestorage;
use Elfcms\Elfcms\Aux\Fragment;
use Elfcms\Elfcms\Aux\FS;
use Elfcms\Elfcms\Aux\Image;
use Elfcms\Elfcms\Models\ElfcmsContact;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Support\Facades\Cookie;

/* Settings */

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

if (!function_exists('phone')) {

    function phone($phone, $code = 49)
    {
        $nums = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($nums,0)) {
            $nums = substr($nums,1);
        }
        return '+' . $code . $nums;
    }
}

/* /Settings */

if (!function_exists('fragment')) {

    function fragment(string $code)
    {
        return Fragment::get($code);
    }
}

/* Image */

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

    function imgResize($filePath, $width = null, $height = null, $coef = 1, $resultFile = null, $gd = false, $maxDimension = null)
    {
        return Image::adaptResize($filePath, $width, $height, $coef, $resultFile, $gd, $maxDimension);
    }
}

if (!function_exists('imgResizeCache')) {

    function imgResizeCache($filePath, $width = null, $height = null, $coef = 1, $maxDimension = null)
    {
        return Image::adaptResizeCache($filePath, $width, $height, $coef, $maxDimension);
    }
}

/* /Image */

/* Files */

if (!function_exists('data_path')) {

    function data_path(string|null $path = null)
    {
        return Files::data_path($path);
    }
}

if (!function_exists('file_path')) {

    function file_path(string|null $file = null, bool $full = false, $disk = null)
    {
        return Files::file_path($file, $full, $disk);
    }
}

if (!function_exists('iconHtml')) {

    function iconHtml(string|null $file = null, int|null $width = null, int|null $height = null, bool $svg = false)
    {
        return Files::iconHtml($file, $width, $height, $svg);
    }
}

if (!function_exists('iconHtmlLocal')) {

    function iconHtmlLocal(string|null $file = null, int|null $width = null, int|null $height = null, bool $svg = false)
    {
        return Files::iconHtmlLocal($file, $width, $height, $svg);
    }
}

/* /Files */

/* Cookies */

if (!function_exists('cookieConsent')) {

    function cookieConsent()
    {
        return json_decode(Cookie::get('cookie_consent'), true);
    }
}
if (!function_exists('cookieIsCategory')) {

    function cookieIsCategory($name)
    {
        $data = json_decode(Cookie::get('cookie_consent'), true);
        if (is_array($data) && !empty($data['categories']) && !empty($data['categories'][$name])) {
            return true;
        }
        return false;
    }
}

if (!function_exists('cookieGet')) {

    function cookieGet($name)
    {
        return Cookie::get($name);
    }
}

/* /Cookies */


/* Filestorage */

if (!function_exists('fsExtension')) { //! To remove

    function fsExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

}

if (!function_exists('fsIcon')) { //! To remove

    function fsIcon($extension) {
        return Filestorage::icon($extension);
    }
}

if (!function_exists('fsMime')) { //! To remove

    function fsMime($file)
    {
        return mime_content_type($file);
    }

}

if (!function_exists('fsFile')) { //! To remove

    function fsFile($file,$asString)
    {
        return Filestorage::file($file,$asString);
    }

}

if (!function_exists('fsPreview')) {

    function fsPreview($file)
    {
        return FS::preview($file);
    }

}

if (!function_exists('fsPublic')) {

    function fsPublic($file)
    {
        return FS::public($file);
    }

}

if (!function_exists('fsPath')) {

    function fsPath($file)
    {
        return FS::path($file);
    }

}

/* /Filestorage */
