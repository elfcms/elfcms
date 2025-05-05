<?php

namespace Elfcms\Elfcms\Services;

use Elfcms\Elfcms\Models\Page;

class DynamicPageRouter
{

    public static function moduleRoutes(Page $page)
    {
        $configs = config('elfcms');
        $pageModules = [];
        if (!empty($configs)) {
            foreach ($configs as $name => $config) {
                if (!empty($config['pages'])) {
                    $pageModules[$name] = $config['pages'];
                }
            }
        }
        if (!empty($pageModules) && !empty($pageModules[$page->module]) && !empty($pageModules[$page->module]['router'])) {
            try {
                $pageModules[$page->module]['router']::moduleRoutes($page);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
