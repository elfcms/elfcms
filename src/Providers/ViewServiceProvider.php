<?php

namespace Elfcms\Elfcms\Providers;

use Elfcms\Elfcms\Aux\Admin\Menu;
use Elfcms\Elfcms\Models\Setting;
use Elfcms\Elfcms\View\Composers\EmailEventComposer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            if (Schema::hasTable('elfcms_settings')) {

                View::composer('*::admin.*', function($view) {
                    $configs = config('elfcms');
                    //$menu = Menu::getByRoute($configs);
                    //dd([$menu,Route::currentRouteName()]);
                    $pageConfig = null;
                    if (!empty($configs)) {
                        foreach ($configs as $package => $config) {
                            if (!empty($config['menu'])) {
                                foreach ($config['menu'] as $item) {
                                    if (empty($item['parent_route'])) {
                                        $item['parent_route'] = $item['route'];
                                    }
                                    if (Str::startsWith(Route::currentRouteName(),$item['parent_route'])) {
                                        $pageConfig = $item;
                                    }
                                }
                            }
                        }
                    }
                    $adminPath = $config['elfcms']['admin_path'] ?? '/admin';
                    $vendorPath = 'elfcms/admin';
                    $cssPath = '/css/style.css';
                    $jsPath = '/js/elf.js';
                    $styles = [];
                    $scripts = [];
                    if (!empty($configs)) {
                        foreach ($configs as $name => $config) {
                            if ($name == 'elfcms') {
                                $name = '';
                            }
                            else {
                                $name = '/modules/' . $name;
                            }
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $vendorPath . $name . $cssPath) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . $vendorPath . $name . $cssPath)) {
                                $styles[] = $vendorPath . $name . $cssPath;
                            }
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $vendorPath . $name . $jsPath) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . $vendorPath . $name . $jsPath)) {
                                $scripts[] = $vendorPath . $name . $jsPath;
                            }
                        }
                    }
                    $view->with('admin_styles',$styles)
                        ->with('admin_scripts',$scripts)
                        ->with('adminPath',$adminPath)
                        ->with('pageConfig',$pageConfig);
                });
                View::composer('*layouts*.main', function($view) {
                    $view->with('elfSiteSettings',Setting::values());
                });
                View::composer('*emails.events.*', EmailEventComposer::class);
            }
        }
        catch (\Exception $e) {
            //
        }
    }
}
