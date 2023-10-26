<?php

namespace Elfcms\Elfcms\Providers;

use Elfcms\Elfcms\Aux\Locales as ElfLocales;
use Elfcms\Elfcms\Console\Commands\ElfcmsInstall;
use Elfcms\Elfcms\Console\Commands\ElfcmsPublish;
use Elfcms\Elfcms\Aux\Locales;
use Elfcms\Elfcms\Console\Commands\ElfcmsDataTypes;
use Elfcms\Elfcms\Console\Commands\ElfcmsEmailEvents;
use Elfcms\Elfcms\Console\Commands\ElfcmsFieldTypes;
use Elfcms\Elfcms\Console\Commands\ElfcmsRoles;
use Elfcms\Elfcms\Console\Commands\ElfcmsSettings;
use Elfcms\Elfcms\Http\Middleware\CookieCheck;
use Elfcms\Elfcms\Http\Middleware\AccountUser;
use Elfcms\Elfcms\Http\Middleware\AdminUser;
use Elfcms\Elfcms\Http\Middleware\VisitStatistics;
use Elfcms\Elfcms\Livewire\AdminImageUpload;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Elfcms\Elfcms\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class ElfcmsModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //$this->app->register(EventServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);

        require_once __DIR__ . '/../Aux/UrlParams.php';
        require_once __DIR__ . '/../Aux/FormSaver.php';
        require_once __DIR__ . '/../Aux/Helpers.php';
        require_once __DIR__ . '/../Aux/Views.php';
        require_once __DIR__ . '/../Aux/Locales.php';
        require_once __DIR__ . '/../Aux/TextPrepare.php';
        require_once __DIR__ . '/../Aux/Admin/Menu.php';

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('UrlParams','Elfcms\Elfcms\Aux\UrlParams');
        $loader->alias('FormSaver','Elfcms\Elfcms\Aux\FormSaver');
        $loader->alias('Helpers','Elfcms\Elfcms\Aux\Helpers');
        $loader->alias('Views','Elfcms\Elfcms\Aux\Views');
        $loader->alias('Locales','Elfcms\Elfcms\Aux\Locales');
        $loader->alias('TextPrepare','Elfcms\Elfcms\Aux\TextPrepare');
        $loader->alias('AdminMenu','Elfcms\Elfcms\Aux\Admin\Menu');

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $moduleDir = dirname(__DIR__);

        $locales = config('elfcms.elfcms.locales');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ElfcmsPublish::class,
                ElfcmsInstall::class,
                ElfcmsRoles::class,
                ElfcmsSettings::class,
                ElfcmsDataTypes::class,
                ElfcmsEmailEvents::class,
                ElfcmsFieldTypes::class,
            ]);
        }
        $this->loadRoutesFrom($moduleDir.'/routes/web.php');
        $this->loadViewsFrom($moduleDir.'/resources/views', 'elfcms');
        $this->loadMigrationsFrom($moduleDir.'/database/migrations');

        $this->loadTranslationsFrom($moduleDir.'/resources/lang', 'elfcms');

        if (!empty($locales) && is_array($locales)) {
            foreach ($locales as $locale) {
                if (!empty($locale['code'])) {
                    $this->publishes([
                        $moduleDir.'/resources/lang/'.$locale['code'].'/validation.php' => resource_path('lang/'.$locale['code'].'/validation.php'),
                    ],'lang');
                }
            }
        }
        /* $this->publishes([
            $moduleDir.'/resources/lang/en/validation.php' => resource_path('lang/en/validation.php'),
        ],'lang');
        $this->publishes([
            $moduleDir.'/resources/lang/de/validation.php' => resource_path('lang/de/validation.php'),
        ],'lang');
        $this->publishes([
            $moduleDir.'/resources/lang/ru/validation.php' => resource_path('lang/ru/validation.php'),
        ],'lang'); */

        $this->publishes([
            $moduleDir.'/resources/lang' => resource_path('lang/elfcms/elfcms'),
        ],'lang');

        $this->publishes([
            $moduleDir.'/config/elfcms.php' => config_path('elfcms/elfcms.php'),
        ],'config');

        $this->publishes([
            $moduleDir.'/resources/views/admin' => resource_path('views/elfcms/admin'),
        ],'admin');
        $this->publishes([
            $moduleDir.'/public/admin' => public_path('elfcms/admin/'),
        ], 'admin');

        $this->publishes([
            $moduleDir.'/resources/views/components' => resource_path('views/elfcms/components'),
        ],'components');

        $this->publishes([
            $moduleDir.'/resources/views/emails' => resource_path('views/elfcms/emails'),
        ],'emails');
        /* $this->publishes([
            $moduleDir.'/resources/views/components' => resource_path('views'),
        ]);
        $this->publishes([
            $moduleDir.'/resources/views/emails' => resource_path('views'),
        ]);
        $this->publishes([
            $moduleDir.'/resources/views/public' => resource_path('views'),
        ]); */
        $this->publishes([
            $moduleDir.'/resources/views/welcome.blade.php' => resource_path('views').'/welcome.blade.php',
        ],'welcome');
        $this->publishes([
            $moduleDir.'/public/welcome' => public_path('elfcms/welcome/'),
        ], 'welcome');
        /* dd([
            $moduleDir.'/resources/views/welcome.blade.php',
            resource_path('views').'/welcome.blade.php'
        ]); */


        /* $this->publishes([
            $moduleDir.'/public/css' => public_path('css'),
        ], 'public');
        $this->publishes([
            $moduleDir.'/public/js' => public_path('js'),
        ], 'public');
        $this->publishes([
            $moduleDir.'/public/images' => public_path('images'),
        ], 'public');
        $this->publishes([
            $moduleDir.'/public/fonts' => public_path('fonts'),
        ], 'public'); */

        config(['auth.providers.users.model' => \Elfcms\Elfcms\Models\User::class]);
        if (config('elfcms.elfcms.disks.elfcmsviews')) {
            config(['filesystems.disks.elfcmsviews' => config('elfcms.elfcms.disks.elfcmsviews')]);
        }
        else {
            config(['filesystems.disks.elfcmsviews' => ['driver' => 'local','root' => base_path('resources/views/vendor/elfcms'),]]);
        }

        try {
            ElfLocales::set();
        }
        catch (\Exception $e) {
            //
        }


        $router->middlewareGroup('admin', array(
            AdminUser::class
        ));

        $router->middlewareGroup('account', array(
            AccountUser::class
        ));

        $router->middlewareGroup('cookie', array(
            CookieCheck::class
        ));

        $router->middlewareGroup('statistics', array(
            VisitStatistics::class
        ));

        Blade::component('elfcms-form', \Elfcms\Elfcms\View\Components\Form::class);
        Blade::component('elfcms-menu', \Elfcms\Elfcms\View\Components\Menu::class);
        Blade::component('elfcms-account-login', \Elfcms\Elfcms\View\Components\Account\Login::class);
        Blade::component('elfcms-account-register', \Elfcms\Elfcms\View\Components\Account\Register::class);
        Blade::component('elfcms-account-edit', \Elfcms\Elfcms\View\Components\Account\Edit::class);
        Blade::component('elfcms-account-getrestore', \Elfcms\Elfcms\View\Components\Account\GetRestore::class);
        Blade::component('elfcms-account-setrestore', \Elfcms\Elfcms\View\Components\Account\SetRestore::class);

        //Livewire::component('admin-image-upload', AdminImageUpload::class);
    }
}
