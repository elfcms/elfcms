<?php

namespace Elfcms\Elfcms\Aux;

use Illuminate\Support\Facades\Blade;

class TextPrepare
{

    public static $openTag = '[[',
        $closeTag = ']]';

    public static function get($text, $params = array(), $openTag = null, $closeTag = null)
    {
        if (empty($openTag)) {
            return $text;
        }
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

    private static function componentList()
    {
        $components = [];
        $config = config('elfcms');
        if ($config) {
            foreach ($config as $module => $data) {
                if (!empty($data['components'])) {
                    $components[$module] = $data['components'];
                }
            }
        }

        return $components;
    }

    public static function components($text)
    {
        $componentList = self::componentList();
        if (empty($componentList)) {
            return $text;
        }

        $pattern = '~\[\[\K.+?(?=\]\])~';
        preg_match_all($pattern, $text, $result);

        $params = [];

        if (!empty($result[0])) {
            foreach ($result[0] as $param) {
                $render = null;
                $paramData = explode(':', $param);
                $componentData = explode('.', $paramData[0]);
                if (isset($componentData[1]) && isset($componentList[$componentData[0]][$componentData[1]]) && !empty($componentList[$componentData[0]][$componentData[1]]['class'])) {
                    if (isset($paramData[1])) {
                        $componentOption = explode(',', $paramData[1]);
                        $component = new $componentList[$componentData[0]][$componentData[1]]['class'](...$componentOption);
                    } else {
                        $component = new $componentList[$componentData[0]][$componentData[1]]['class']();
                    }
                    if ($component) {
                        $render = Blade::renderComponent($component);
                    }
                }
                if ($render) {
                    $params[$param] = $render;
                } else {
                    $params[$param] = '[[' . $param . ']]';
                }
            }
        }
        return self::get($text, $params, '[[', ']]');
    }
}
