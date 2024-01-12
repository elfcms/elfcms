<?php

namespace Elfcms\Elfcms\Aux\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public static function accessGet()
    {
        $user = Auth::user();

        $userPerms = Permissions::userRoutes($user,false);

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
                        $access = false;
                        foreach ($userPerms as $route => $data) {
                            if (Str::startsWith($item['parent_route'], $route) || Str::startsWith($route, $item['parent_route'])) {
                                if (!empty($data['permissions']) && ($data['permissions']['read'] > 0 || $data['permissions']['write'] > 0)) {
                                    $access = true;
                                    break;
                                }
                            }
                        }
                        if (!$access) {
                            continue;
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
