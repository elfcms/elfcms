<?php

namespace Elfcms\Elfcms\Aux;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Views
{

    public static $alias = '';

    public static function get($path = null, $disk = 'elfcmsviews')
    {
        if (mb_substr($path, 0, 1) == '/') {
            $path =  substr_replace($path, '', 0, 1);
        }

        /* if (strpos($path, self::$alias . '/') === 0) {

            $path = substr_replace($path, '', 0, strlen(self::$alias . '/'));
        } */
        self::$alias = trim(self::$alias, '/');

        if (self::$alias != '') {
            self::$alias .= '/';
        }

        $list = Storage::disk($disk)->files(self::$alias . $path, true);
        $result = [];

        foreach ($list as $file) {
            if (!Str::endsWith($file, '.blade.php')) continue;
            $result[] = [
                'name' => str_replace('/', '.', substr_replace(str_replace('.blade.php', '', str_replace('resources.views.','',$file)), self::$alias . '::', 0, strlen(self::$alias . '/'))),
                'path' => $file,
            ];
        }

        return $result;
    }

    public static function list($path = null, $disk = 'elfcmsviews')
    {
        self::$alias = trim(self::$alias, '/');

        if (self::$alias != '') {
            self::$alias .= '/';
        }

        $path = trim($path, '/');

        $list = [];
        try {
            $list = Storage::disk($disk)->files(self::$alias. $path, true);
        }
        catch(\Exception $e) {
            //
        }

        $result = [];

        $alias = 'elfcms';
        if (self::$alias != '')  {
            $alias = self::$alias;
        }
        foreach ($list as $file) {
            if (!Str::endsWith($file, '.blade.php')) continue;
            $result[] =  str_replace('/', '.', substr_replace(str_replace('.blade.php', '', str_ireplace('resources/views/','',$file)), $alias . '::', 0, strlen(self::$alias)));
        }

        return $result;
    }
}
