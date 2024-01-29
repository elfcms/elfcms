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
use Elfcms\Elfcms\Console\Commands\ElfcmsSite;
use Elfcms\Elfcms\Http\Middleware\CookieCheck;
use Elfcms\Elfcms\Http\Middleware\AccountUser;
use Elfcms\Elfcms\Http\Middleware\AdminAccess;
use Elfcms\Elfcms\Http\Middleware\AdminUser;
use Elfcms\Elfcms\Http\Middleware\VisitStatistics;
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
        $this->app->register(EventServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
        $this->app->register(SitePublicProvider::class);

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
                ElfcmsSite::class,
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
            $moduleDir.'/resources/views/public/layouts' => resource_path('views/public/layouts'),
        ],'emails');
        $this->publishes([
            $moduleDir.'/resources/views/public/pages' => resource_path('views/public/pages'),
        ],'emails'); */
        $this->publishes([
            $moduleDir.'/resources/views/welcome.blade.php' => resource_path('views').'/welcome.blade.php',
        ],'welcome');
        $this->publishes([
            $moduleDir.'/public/welcome' => public_path('elfcms/welcome/'),
        ], 'welcome');

        config(['auth.providers.users.model' => \Elfcms\Elfcms\Models\User::class]);
        $disks = config('elfcms.elfcms.disks');
        if (!empty($disks)) {
            foreach ($disks as $name => $disk) {
                config(["filesystems.disks.$name" => config("elfcms.elfcms.disks.$name")]);
            }
        }
        if (!config('elfcms.elfcms.disks.elfcmsviews')) {
            config(['filesystems.disks.elfcmsviews' => ['driver' => 'local','root' => base_path('vendor/elfcms/elfcms/src/resources/views')]]);
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

        $router->middlewareGroup('access', array(
            AdminAccess::class
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
        Blade::component('elfcms-fragment', \Elfcms\Elfcms\View\Components\Fragment::class);
        Blade::component('elfcms-message', \Elfcms\Elfcms\View\Components\Message::class);
        Blade::component('elfcms-input-checkbox', \Elfcms\Elfcms\View\Components\Input\Checkbox::class);
        Blade::component('elfcms-input-file', \Elfcms\Elfcms\View\Components\Input\File::class);
        Blade::component('elfcms-input-image', \Elfcms\Elfcms\View\Components\Input\Image::class);
        Blade::component('elfcms-input-image-alt', \Elfcms\Elfcms\View\Components\Input\ImageAlternate::class);
        Blade::component('elfcms-account-login', \Elfcms\Elfcms\View\Components\Account\Login::class);
        Blade::component('elfcms-account-register', \Elfcms\Elfcms\View\Components\Account\Register::class);
        Blade::component('elfcms-account-edit', \Elfcms\Elfcms\View\Components\Account\Edit::class);
        Blade::component('elfcms-account-getrestore', \Elfcms\Elfcms\View\Components\Account\GetRestore::class);
        Blade::component('elfcms-account-setrestore', \Elfcms\Elfcms\View\Components\Account\SetRestore::class);

        //Livewire::component('admin-image-upload', AdminImageUpload::class);
    }
}
