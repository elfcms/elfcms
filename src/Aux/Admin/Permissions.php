<?php

namespace Elfcms\Elfcms\Aux\Admin;

use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\RolePermission;

class Permissions
{

    public static function routes()
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
                $accessRoutes[$moduleName] = $routes;
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

    public static function roleRoutes(Role $role)
    {
        $routes = self::routes();
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
            $roleRoutes[$moduleName] = $moduleRoutes;
        }

        return $roleRoutes;
    }
}
