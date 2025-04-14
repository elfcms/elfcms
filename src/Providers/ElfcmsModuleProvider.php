<?php

namespace Elfcms\Elfcms\Providers;

use Elfcms\Elfcms\Aux\Locales as ElfLocales;
use Elfcms\Elfcms\Console\Commands\ElfcmsInstall;
use Elfcms\Elfcms\Console\Commands\ElfcmsPublish;
use Elfcms\Elfcms\Aux\Locales;
use Elfcms\Elfcms\Console\Commands\ElfcmsBackup;
use Elfcms\Elfcms\Console\Commands\ElfcmsBackupFileExists;
use Elfcms\Elfcms\Console\Commands\ElfcmsDataTypes;
use Elfcms\Elfcms\Console\Commands\ElfcmsEmailEvents;
use Elfcms\Elfcms\Console\Commands\ElfcmsFieldTypes;
use Elfcms\Elfcms\Console\Commands\ElfcmsRestore;
use Elfcms\Elfcms\Console\Commands\ElfcmsRoles;
use Elfcms\Elfcms\Console\Commands\ElfcmsSettings;
use Elfcms\Elfcms\Console\Commands\ElfcmsSite;
use Elfcms\Elfcms\Http\Middleware\CookieCheck;
use Elfcms\Elfcms\Http\Middleware\AccountUser;
use Elfcms\Elfcms\Http\Middleware\AdminAccess;
use Elfcms\Elfcms\Http\Middleware\AdminUser;
use Elfcms\Elfcms\Http\Middleware\Maintenance;
use Elfcms\Elfcms\Http\Middleware\SetLocale;
use Elfcms\Elfcms\Http\Middleware\VisitStatistics;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Elfcms\Elfcms\Providers\EventServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
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
        $loader->alias('UrlParams', 'Elfcms\Elfcms\Aux\UrlParams');
        $loader->alias('FormSaver', 'Elfcms\Elfcms\Aux\FormSaver');
        $loader->alias('Helpers', 'Elfcms\Elfcms\Aux\Helpers');
        $loader->alias('Views', 'Elfcms\Elfcms\Aux\Views');
        $loader->alias('Locales', 'Elfcms\Elfcms\Aux\Locales');
        $loader->alias('TextPrepare', 'Elfcms\Elfcms\Aux\TextPrepare');
        $loader->alias('AdminMenu', 'Elfcms\Elfcms\Aux\Admin\Menu');

        $helpers = glob(base_path() . '/*/elfcms/*/src/Helpers/*{.php}', GLOB_BRACE | GLOB_MARK);

        foreach ($helpers as $helper) {
            require_once $helper;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $moduleDir = dirname(__DIR__);

        $config = config('elfcms.elfcms');
        $locales = config('elfcms.elfcms.locales');

        backupSetConfig(backupSettings());

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
                ElfcmsBackup::class,
                ElfcmsRestore::class,
                ElfcmsBackupFileExists::class,
            ]);
        }
        $this->commands([
            ElfcmsBackup::class,
            ElfcmsRestore::class,
        ]);

        $this->app->booted(function () {
            /* if (config('elfcms.elfcms.backup.enabled')) {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('elfcms:backup')->cron(config('elfcms.elfcms.backup.schedule'));
            } */
            $this->scheduleCommands();
        });

        $this->loadRoutesFrom($moduleDir . '/routes/web.php');
        $this->loadViewsFrom($moduleDir . '/resources/views', 'elfcms');
        $this->loadMigrationsFrom($moduleDir . '/database/migrations');

        $this->loadTranslationsFrom($moduleDir . '/resources/lang', 'elfcms');

        if (!empty($locales) && is_array($locales)) {
            foreach ($locales as $locale) {
                if (!empty($locale['code'])) {
                    $this->publishes([
                        $moduleDir . '/resources/lang/' . $locale['code'] . '/validation.php' => resource_path('lang/' . $locale['code'] . '/validation.php'),
                    ], 'lang');
                }
            }
        }

        $this->publishes([
            $moduleDir . '/resources/lang' => resource_path('lang/elfcms/elfcms'),
        ], 'lang');

        $this->publishes([
            $moduleDir . '/config/elfcms.php' => config_path('elfcms/elfcms.php'),
        ], 'config');

        $this->publishes([
            $moduleDir . '/resources/views/admin' => resource_path('views/elfcms/admin'),
        ], 'admin');
        $this->publishes([
            $moduleDir . '/public/admin' => public_path('elfcms/admin/'),
        ], 'admin');
        $this->publishes([
            $moduleDir . '/resources/views/public' => resource_path('views/elfcms/public'),
        ], 'public');

        $this->publishes([
            $moduleDir . '/resources/views/components' => resource_path('views/elfcms/components'),
        ], 'components');

        $this->publishes([
            $moduleDir . '/resources/views/emails' => resource_path('views/elfcms/emails'),
        ], 'emails');

        $this->publishes([
            $moduleDir . '/resources/views/welcome.blade.php' => resource_path('views') . '/welcome.blade.php',
        ], 'welcome');

        $this->publishes([
            $moduleDir . '/public/welcome' => public_path('elfcms/welcome/'),
        ], 'welcome');

        $this->publishes([
            $moduleDir . '/resources/views/maintenance.blade.php' => resource_path('views') . '/maintenance.blade.php',
        ], 'maintenance');

        $this->publishes([
            $moduleDir . '/public/maintenance' => public_path('elfcms/maintenance/'),
        ], 'maintenance');


        config(['auth.providers.users.model' => \Elfcms\Elfcms\Models\User::class]);
        $disks = config('elfcms.elfcms.disks');
        if (!empty($disks)) {
            foreach ($disks as $name => $disk) {
                config(["filesystems.disks.$name" => $config['disks'][$name]]); //config("elfcms.elfcms.disks.$name")]);
            }
        }
        if (!config('elfcms.elfcms.disks.elfcmsviews')) {
            config(['filesystems.disks.elfcmsviews' => ['driver' => 'local', 'root' => base_path('vendor/elfcms/elfcms/src/resources/views')]]);
        }

        if (config('logging.channels')) {
            foreach (config('logging.channels') as $channel => $data) {
                config(["logging.channels.$channel" => $data]);
            }
        }
        if (!config('logging.channels.elfauth')) {
            config(['logging.channels.elfauth' => [
                'driver' => 'single',
                'path' => storage_path('logs/elfauth.log'),
                'level' => 'info',
            ]]);
        }
        if (!config('logging.channels.backup')) {
            config(['logging.channels.backup' => [
                'driver' => 'single',
                'path' => storage_path('logs/backup.log'),
                'level' => 'info',
            ]]);
        }

        if (!empty($config['links'])) {
            foreach ($config['links'] as $name => $link) {
                config(["filesystems.links.$name" => $link]);
            }
        }

        try {
            ElfLocales::set();
        } catch (\Exception $e) {
            //
        }


        $router->pushMiddlewareToGroup('web', Maintenance::class);

        $router->middlewareGroup('maintenance', array(
            Maintenance::class
        ));

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

        $router->middlewareGroup('locales', array(
            SetLocale::class
        ));

        Blade::component('elfcms-cookie-consent', \Elfcms\Elfcms\View\Components\CookieConsent::class);
        Blade::component('elfcms-form', \Elfcms\Elfcms\View\Components\Form::class);
        Blade::component('elfcms-menu', \Elfcms\Elfcms\View\Components\Menu::class);
        Blade::component('elfcms-fragment', \Elfcms\Elfcms\View\Components\Fragment::class);
        Blade::component('elfcms-message', \Elfcms\Elfcms\View\Components\Message::class);
        Blade::component('elfcms-input-checkbox', \Elfcms\Elfcms\View\Components\Input\Checkbox::class);
        Blade::component('elfcms-input-file', \Elfcms\Elfcms\View\Components\Legacy\Input\File::class);
        Blade::component('elfcms-input-fsfile', \Elfcms\Elfcms\View\Components\Input\FSFile::class);
        Blade::component('elfcms-input-fileext', \Elfcms\Elfcms\View\Components\Input\FileExt::class);
        Blade::component('elfcms-input-image', \Elfcms\Elfcms\View\Components\Input\Image::class);
        Blade::component('elfcms-input-image-alt', \Elfcms\Elfcms\View\Components\Input\ImageAlternate::class);
        Blade::component('elfcms-account-login', \Elfcms\Elfcms\View\Components\Account\Login::class);
        Blade::component('elfcms-account-register', \Elfcms\Elfcms\View\Components\Account\Register::class);
        Blade::component('elfcms-account-edit', \Elfcms\Elfcms\View\Components\Account\Edit::class);
        Blade::component('elfcms-account-getrestore', \Elfcms\Elfcms\View\Components\Account\GetRestore::class);
        Blade::component('elfcms-account-setrestore', \Elfcms\Elfcms\View\Components\Account\SetRestore::class);

        Blade::component('elf-input-file', \Elfcms\Elfcms\View\Components\Input\File::class);
        Blade::component('elf-notify', \Elfcms\Elfcms\View\Components\Notify::class);
    }

    protected function scheduleCommands()
    {
        $schedule = $this->app->make(Schedule::class);
        if (config('elfcms.elfcms.backup.enabled')) {
            $schedule->command('elfcms:backup-file-exists')
                ->dailyAt('01:00')
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/backup_check.log'));
            $schedule->command('elfcms:backup')->cron(config('elfcms.elfcms.backup.schedule'))->withoutOverlapping();
        }
        $workerLauncher = config('elfcms.elfcms.system.worker', 'supervisor');
        if ($workerLauncher == 'sheduler') {
            $schedule->command('queue:work --once')->everyMinute();
        }
    }
}
