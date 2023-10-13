<?php

namespace Elfcms\Elfcms\Aux;

use Illuminate\Support\Facades\Storage;

class Views
{

    public static $alias = '';

    public static function get($path = null)
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

        $list = Storage::disk('elfcmsviews')->files(self::$alias . $path, true);
        $result = [];

        foreach ($list as $file) {
            $result[] = [
                'name' => str_replace('/', '.', substr_replace(str_replace('.blade.php', '', $file), self::$alias . '::', 0, strlen(self::$alias . '/'))),
                'path' => $file,
            ];
        }

        return $result;
    }

    public static function list($path = null)
    {
        self::$alias = trim(self::$alias, '/');

        if (self::$alias != '') {
            self::$alias .= '/';
        }

        $path = trim($path, '/');


        $list = Storage::disk('elfcmsviews')->files(self::$alias. $path, true);

        $result = [];

        $alias = 'elfcms';
        if (self::$alias != '')  {
            $alias = self::$alias;
        }
        foreach ($list as $file) {
            $result[] = str_replace('/', '.', substr_replace(str_replace('.blade.php', '', $file), $alias . '::', 0, strlen(self::$alias)));
        }

        return $result;
    }
}
