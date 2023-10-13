<?php

namespace Elfcms\Elfcms\Aux\Admin;

class Menu
{

    public static function get()
    {
        $menu = [];
        $configs = config('elfcms');
        if (!empty($configs)) {
            $position = 0;
            foreach ($configs as $package => $config) {
                if (!empty($config['menu'])) {
                    foreach ($config['menu'] as $item) {
                        if (empty($item['parent_route'])) {
                            $item['parent_route'] = $item['route'];
                        }
                        if (!empty($item['position'])) {
                            $position = $item['position'];
                        } else {
                            $position = $position + 10;
                        }
                        $menu[$position] = $item;
                    }
                }
            }
        }
        ksort($menu);
        return $menu;
    }
}
