<?php

namespace Elfcms\Elfcms\Providers;

use Elfcms\Elfcms\Models\Setting;
use Elfcms\Elfcms\View\Composers\EmailEventComposer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            if (Schema::hasTable('settings')) {

                View::composer('*::admin.*', function($view) {
                    $configs = config('elfcms');
                    $vendorPath = '';
                    $cssPath = '/admin/css/style.css';
                    $jsPath = '/admin/js/elf.js';
                    $styles = [];
                    $scripts = [];
                    if (!empty($configs)) {
                        foreach ($configs as $name => $config) {
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $vendorPath . $name . $cssPath) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . $vendorPath . $name . $cssPath)) {
                                $styles[] = $vendorPath . $name . $cssPath;
                            }
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $vendorPath . $name . $jsPath) || file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . $vendorPath . $name . $jsPath)) {
                                $scripts[] = $vendorPath . $name . $jsPath;
                            }
                        }
                    }
                    $view->with('admin_styles',$styles)
                        ->with('admin_scripts',$scripts);
                });
                View::composer('*layouts*.main', function($view) {
                    $view->with('elfSiteSettings',Setting::values());
                });
                View::composer('*::emails.events.*', EmailEventComposer::class);
            }
        }
        catch (\Exception $e) {
            //
        }
    }
}
