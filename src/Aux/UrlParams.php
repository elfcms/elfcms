<?php

namespace Elfcms\Elfcms\Aux;

use Illuminate\Support\Arr;

class UrlParams
{

    public static function add(array $params)
    {
        return Arr::query(self::addArr($params));
    }

    public static function addArr(array $params)
    {
        $query = request()->query();

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                if (count($value) == 1) {
                    $params[$key] = $value[0];
                } else {
                    if (isset($query[$key]) && $query[$key] == $value[0]) {
                        $params[$key] = $value[1];
                    } else {
                        $params[$key] = $value[0];
                    }
                }
            }
        }

        return array_merge($query, $params);
    }

    public static function case(string $param, array $values, $default = false)
    {
        $query = request()->query();
        $result = $default;

        if (isset($query[$param])) {
            foreach ($values as $key => $value) {
                if ($query[$param] == $key) {
                    $result = $value;
                }
            }
        }

        return $result;
    }
}
