<?php

namespace Elfcms\Elfcms\Aux\Admin;

use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\RolePermission;
use Elfcms\Elfcms\Models\User;

class Permissions
{

    public static function routes($byModule = true)
    {
        $configs = config('elfcms');
        $accessRoutes = [];
        foreach ($configs as $moduleName => $directories) {
            if (isset($directories['access_routes'])) {
                $routes = [];
                foreach ($directories['access_routes'] as $route) {
                    $title = __($route['lang_title']);
                    if ($title == $route['lang_title']) {
                        $title = $route['title'] ?? $route['route'];
                    }
                    $route['title'] = $title;
                    $route['name'] = str_replace('.', '_', $route['route']);
                    $route['permissions'] = [];
                    $routes[$route['route']] = $route;
                }
                if ($byModule) {
                    $accessRoutes[$moduleName] = $routes;
                }
                else {
                    $accessRoutes = array_merge($accessRoutes, $routes);
                }
            }
        }

        return $accessRoutes;
    }

    public static function rolePermissions(Role $role)
    {
        $permissions = RolePermission::where('role_id', $role->id)->get()->toArray();

        $routesPermissions = [];

        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                $routesPermissions[$permission['route']] = $permission;
            }
        }

        return $routesPermissions;
    }

    public static function roleRoutes(Role $role, $byModule = true)
    {
        $routes = self::routes($byModule);
        $permissions =self::rolePermissions($role);
        $roleRoutes = [];

        foreach ($routes as $moduleName => $moduleRoutes) {
            foreach($permissions as $routeName => $permission) {
                if (isset($moduleRoutes[$routeName])) {
                    $moduleRoutes[$routeName]['permissions'] = [
                        'read' => $permission['read'],
                        'write' => $permission['write']
                    ];
                }
            }
            if ($byModule) {
                $roleRoutes[$moduleName] = $moduleRoutes;
            }
            else {
                $roleRoutes = array_merge($moduleRoutes, $roleRoutes);
            }
        }

        return $roleRoutes;
    }



    public static function userPermissions(User $user)
    {
        $routesPermissions = [];
        foreach ($user->roles as $role) {
            $permissions = RolePermission::where('role_id', $role->id)->get()->toArray();

            if (!empty($permissions)) {
                foreach ($permissions as $permission) {if (!empty($routesPermissions[$permission['route']])) {
                        $routesPermissions[$permission['route']] = [
                            'read' => $routesPermissions[$permission['route']]['read'] + $permission['read'],
                            'write' => $routesPermissions[$permission['route']]['write'] + $permission['write'],
                        ];
                    }
                    else {
                        $routesPermissions[$permission['route']] = [
                            'read' => $permission['read'],
                            'write' => $permission['write'],
                        ];
                    }
                }
            }
        }

        return $routesPermissions;
    }

    public static function userRoutes(User $user, $byModule = true)
    {
        $routes = self::routes($byModule);
        $permissions =self::userPermissions($user);
        $isAdmin = $user->roles()->where('role_id',1)->exists();
        $userRoutes = [];

        if ($byModule) {
            foreach ($routes as $moduleName => $moduleRoutes) {
                foreach($moduleRoutes as $routeName => $routeData) {
                    if (isset($permissions[$routeName])) {
                        $routeData['permissions'] = $permissions[$routeName];
                        $userRoutes[$moduleName][$routeName] = $routeData;
                    }
                    elseif ($isAdmin) $userRoutes[$moduleName][$routeName]['permissions'] = ['read'=>1, 'write'=>1];
                }
            }
        }
        else {
            foreach ($routes as $routeName => $routeData) {
                if (isset($permissions[$routeName])) {
                    $routeData['permissions'] = $permissions[$routeName];
                    $userRoutes[$routeName] = $routeData;
                }
                elseif ($isAdmin) $userRoutes[$routeName]['permissions'] = ['read'=>1, 'write'=>1];
            }
        }

        return $userRoutes;
    }

}
