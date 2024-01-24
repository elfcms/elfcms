<?php

namespace Elfcms\Elfcms\Providers;

use Illuminate\Support\ServiceProvider;

class SitePublicProvider extends ServiceProvider
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
        $moduleDir = dirname(__DIR__);

        $this->publishes([
            $moduleDir.'/resources/views/public/sites/default/' => resource_path('views/public'),
        ],'site_defalt');

        $this->publishes([
            $moduleDir.'/public/sites/default/css' => public_path('css/'),
        ], 'site');

        $this->publishes([
            $moduleDir.'/public/sites/default/js' => public_path('js/'),
        ], 'site');

        $this->publishes([
            $moduleDir.'/public/sites/default/images' => public_path('images/'),
        ], 'site');

        $this->publishes([
            $moduleDir.'/public/sites/default/fonts' => public_path('fonts/'),
        ], 'site');



    }
}
