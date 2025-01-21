<?php

use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Elfcms\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

$adminPath = config('elfcms.elfcms.admin_path') ?? '/admin';

Route::group(['middleware' => ['web', 'locales', 'cookie']], function () use ($adminPath) {

    Route::get('/maintenance', Elfcms\Elfcms\Http\Controllers\MaintenanceController::class)->name('maintenance');

    /* ---- Cookie consent ---- */

    Route::post('/cookie-consent', Elfcms\Elfcms\Http\Controllers\CookieConsentController::class)->name('cookie-consent');

    /* ------------------------ */

    /* Admin panel */
    Route::name('admin.')->middleware(['admin', 'access'])->group(function () use ($adminPath) {

        Route::get($adminPath . '', [Elfcms\Elfcms\Http\Controllers\AdminController::class, 'index'])
            ->name('index');
        Route::get($adminPath . '/login', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'index'])
            ->name('login');
        Route::post($adminPath . '/login', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'login']);
        Route::get($adminPath . '/logout', function () use ($adminPath) {
            Auth::logout();
            return redirect($adminPath . '/login');
        })->name('logout');

        Route::get($adminPath . '/forgot-password', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'getRestoreForm'])->name('getrestore');
        Route::post($adminPath . '/forgot-password', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'getRestore']);

        Route::get($adminPath . '/reset-password/{token}', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'setRestoreForm'])->name('setrestore');
        Route::post($adminPath . '/reset-password', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'setRestore'])->name('setnewpassword');

        /* ------------------------ */

        Route::name('settings.')->group(function () use ($adminPath) {
            Route::get($adminPath . '/settings', [Elfcms\Elfcms\Http\Controllers\SettingController::class, 'index'])->name('index');
            Route::post($adminPath . '/settings', [Elfcms\Elfcms\Http\Controllers\SettingController::class, 'save'])->name('save');
        });

        Route::name('cookie-settings.')->group(function () use ($adminPath) {
            Route::get($adminPath . '/cookie-settings', [Elfcms\Elfcms\Http\Controllers\CookieSettingController::class, 'index'])->name('index');
            Route::post($adminPath . '/cookie-settings', [Elfcms\Elfcms\Http\Controllers\CookieSettingController::class, 'save'])->name('save');
        });

        Route::resource($adminPath . '/user/roles', Elfcms\Elfcms\Http\Controllers\Resources\RoleController::class)->names([
            'index' => 'user.roles',
            'create' => 'user.roles.create',
            'edit' => 'user.roles.edit',
            'store' => 'user.roles.store',
            'show' => 'user.roles.show',
            'edit' => 'user.roles.edit',
            'update' => 'user.roles.update',
            'destroy' => 'user.roles.destroy'
        ]);
        Route::resource($adminPath . '/user/users', Elfcms\Elfcms\Http\Controllers\Resources\UserController::class)->names([
            'index' => 'user.users',
            'create' => 'user.users.create',
            'edit' => 'user.users.edit',
            'store' => 'user.users.store',
            'show' => 'user.users.show',
            'edit' => 'user.users.edit',
            'update' => 'user.users.update',
            'destroy' => 'user.users.destroy'
        ]);

        Route::get($adminPath . '/ajax/json/lang/{name}', function (Request $request, $name) {
            $result = [];
            if ($request->ajax()) {
                if (Lang::has('elfcms::' . $name)) {
                    $result = Lang::get('elfcms::' . $name);
                }
            }
            return json_encode($result);
        });


        Route::name('email.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/email/addresses', Elfcms\Elfcms\Http\Controllers\Resources\EmailAddressController::class)->names(['index' => 'addresses']);
            Route::resource($adminPath . '/email/events', Elfcms\Elfcms\Http\Controllers\Resources\EmailEventController::class)->names(['index' => 'events']);
            //Route::resource($adminPath . '/email/templates', Elfcms\Elfcms\Http\Controllers\Resources\EmailTemplateController::class)->names(['index' => 'templates']);
        });

        Route::name('form.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/form/forms', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names(['index' => 'forms']);
            Route::resource($adminPath . '/form/groups', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldGroupController::class)->names(['index' => 'groups']);
            Route::resource($adminPath . '/form/fields', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldController::class)->names(['index' => 'fields']);
            Route::resource($adminPath . '/form/options', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldOptionController::class)->names(['index' => 'options']);
            Route::resource($adminPath . '/form/field-types', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names(['index' => 'field-types']);
            Route::resource($adminPath . '/form/results', Elfcms\Elfcms\Http\Controllers\Resources\FormResultController::class)->names(['index' => 'results']);
        });

        Route::name('forms.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/forms', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::resource($adminPath . '/forms/{form}/groups', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldGroupController::class)->names(['index' => 'groups']);
            Route::resource($adminPath . '/forms/{form}/fields', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldController::class)
                ->names(['index' => 'fields']);
            Route::resource($adminPath . '/forms/{form}/fields/{field}/options', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldOptionController::class)->names(['index' => 'options']);
            //Route::resource($adminPath . '/forms/{form}/results', Elfcms\Elfcms\Http\Controllers\Resources\FormResultController::class)->names(['index' => 'results']);
        });
        Route::name('form-results.')->group(function () use ($adminPath) {
            Route::get($adminPath . '/form-results', [Elfcms\Elfcms\Http\Controllers\FormResultController::class, 'index'])->name('index');
            Route::get($adminPath . '/form-results/{form}', [Elfcms\Elfcms\Http\Controllers\FormResultController::class, 'form'])->name('form');
            Route::get($adminPath . '/form-results/{form}/{result}', [Elfcms\Elfcms\Http\Controllers\FormResultController::class, 'show'])->name('show');
        });

        Route::name('menus.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/menus', Elfcms\Elfcms\Http\Controllers\Resources\MenuController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::resource($adminPath . '/menus/{menu}/items', Elfcms\Elfcms\Http\Controllers\Resources\MenuItemController::class)->names([
                'index' => 'items',
                'create' => 'items.create',
                'edit'   => 'items.edit',
                'store' => 'items.store',
                'show' => 'items.show',
                'update' => 'items.update',
                'destroy' => 'items.destroy'
            ]);
        });

        Route::name('filestorage.')->group(function () use ($adminPath) {
            //Route::name('storages.')->group(function () use ($adminPath) {
            //});
            //Route::name('groups.')->group(function () use ($adminPath) {
                Route::resource($adminPath . '/filestorage/groups', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageFilegroupController::class);
            //});
            //Route::name('types.')->group(function () use ($adminPath) {
                Route::resource($adminPath . '/filestorage/types', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageFiletypeController::class);
            //});
            //Route::name('files.')->group(function () use ($adminPath) {
                Route::resource($adminPath . '/filestorage/files', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageFileController::class);
            //});
            /* Route::get($adminPath . '/filestorage', function(){
                $f = file_get_contents(storage_path('app/public/test/65bbda84004a3.jpg'));
                //$f = file_get_contents(storage_path('app/public/00_Aufforderung_Angebotsabgabe.pdf'));
                //$f = file_get_contents(storage_path('app/public/03_Only_Time.mp3'));
                header('Content-Type: image/jpeg');
                //header('Content-Type: application/pdf'); //application/pdf
                //header('Content-Type: audio/mpeg');
                echo $f;
                //dd(config());
                //dd(mime_content_type(storage_path('app/public/03_Only_Time.mp3')));
            })->name('index'); */
            /* Route::get($adminPath . '/form-results', [Elfcms\Elfcms\Http\Controllers\FormResultController::class,'index'])->name('index');
            Route::get($adminPath . '/form-results/{form}', [Elfcms\Elfcms\Http\Controllers\FormResultController::class,'form'])->name('form');
            Route::get($adminPath . '/form-results/{form}/{result}', [Elfcms\Elfcms\Http\Controllers\FormResultController::class,'show'])->name('show'); */

            Route::resource($adminPath . '/filestorage', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
        });

        Route::name('page.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/page/pages', Elfcms\Elfcms\Http\Controllers\Resources\PageController::class)->names(['index' => 'pages']);
        });

        Route::name('messages.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/messages', Elfcms\Elfcms\Http\Controllers\Resources\MessageController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
        });
        Route::name('fragment.')->group(function () use ($adminPath) {
            Route::resource($adminPath . '/fragment/items', \Elfcms\Elfcms\Http\Controllers\Resources\FragmentItemController::class)->names(['index' => 'items']);
        });

        Route::name('statistics.')->group(function () use ($adminPath) {
            Route::get($adminPath . '/statistics', [Elfcms\Elfcms\Http\Controllers\VisitStatisticController::class, 'index'])->name('index');
        });


        Route::name('ajax.')->group(function () {

            Route::name('form.')->group(function () {
                Route::post('/elfcms/api/form/{form}/grouporder', [Elfcms\Elfcms\Http\Controllers\Ajax\FormController::class, 'groupOrder']);
                Route::post('/elfcms/api/form/{form}/fieldorder', [Elfcms\Elfcms\Http\Controllers\Ajax\FormController::class, 'fieldOrder']);
            });

            Route::name('menu.')->group(function () {
                Route::post('/elfcms/api/menu/{menu}/itemorder', [Elfcms\Elfcms\Http\Controllers\Ajax\MenuController::class, 'itemOrder']);
            });

            Route::name('fragment.')->group(function () {
                Route::get('/elfcms/api/fragment/datatypes', [Elfcms\Elfcms\Http\Controllers\Ajax\FragmentDataTypeController::class, 'get']);
            });

            Route::name('csrf.')->group(function () {
                Route::get('/elfcms/api/csrf', [Elfcms\Elfcms\Http\Controllers\Ajax\CSRFController::class, 'get']);
            });
        });
    });
    /* /Admin panel */

    /* Form processing */
    Route::post('/form/send', [\Elfcms\Elfcms\Http\Controllers\Publics\FormSendController::class, 'send'])->name('form-send');
    Route::get('/form/result', [\Elfcms\Elfcms\Http\Controllers\Publics\FormSendController::class, 'result'])->name('form-result');
    /* /Form processing */

    /* Public */

    /* Dynamic pages */
    Route::get(config('elfcms.elfcms.page_path') . '/{page:slug}', [\Elfcms\Elfcms\Http\Controllers\Publics\PageController::class, 'get']);

    try {
        if (Schema::hasTable('pages')) {
            $pages = Page::where('is_dynamic', '<>', 1)->where('active', 1)->whereNotNull('path')->get();
            foreach ($pages as $page) {

                Route::get($page->path, function () use ($page) {
                    $controller = new \Elfcms\Elfcms\Http\Controllers\Publics\PageController;
                    $template = $page->template;
                    if (empty($template)) {
                        $template = 'default';
                    }
                    return $controller->get($page, dynamic: false, template: $template);
                });
            }
        }
    } catch (\Exception $e) {
        //
    }
});
