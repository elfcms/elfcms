<?php

namespace Elfcms\Elfcms\Aux;

class Helpers
{

    public static function hashTag(string $string, $hash = true)
    {
        $result = strtolower(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $string));

        if ($hash) {
            $result = '#' . $result;
        }

        return $result;
    }

    public static function objectArrayValue(object $object, array $array, $empty = null)
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $object->$key = $value ?? $empty;
            }
        }
        return $object;
    }

    public static function templateText($text, $params = array(), $openTag = '[[', $closeTag = ']]')
    {
        $result = $text;
        if (empty($closeTag)) {
            $closeTag = $openTag;
        }
        if (!empty($params)) {
            $input = array();
            $output = array();
            $i = 0;
            foreach ($params as $param => $value) {
                $input[$i] = $openTag . $param . $closeTag;
                $output[$i] = $value;
                $i++;
            }
            if ($i > 0) {
                $result = str_replace($input, $output, $text);
            }
        }
        return $result;
    }

    public static function test()
    {
        $debug = debug_backtrace();
        dd($debug);
    }
}
