<?php

use Elfcms\Elfcms\Http\Controllers\Admin\BackupAjaxController;
use Elfcms\Elfcms\Http\Controllers\Admin\BackupController;
use Elfcms\Elfcms\Http\Controllers\AdminController;
use Elfcms\Elfcms\Http\Controllers\Ajax\FilestorageFileController;
use Elfcms\Elfcms\Http\Controllers\Ajax\FilestorageFilegroupController;
use Elfcms\Elfcms\Http\Controllers\Ajax\FilestorageFiletypeController;
use Elfcms\Elfcms\Http\Controllers\CookieSettingController;
use Elfcms\Elfcms\Http\Controllers\FormResultController;
use Elfcms\Elfcms\Http\Controllers\LoginController;
use Elfcms\Elfcms\Http\Controllers\Publics\FilestorageFile;
use Elfcms\Elfcms\Http\Controllers\Publics\FormSendController;
use Elfcms\Elfcms\Http\Controllers\Publics\PageController;
use Elfcms\Elfcms\Http\Controllers\SettingController;
use Elfcms\Elfcms\Http\Controllers\SystemController;
use Elfcms\Elfcms\Models\Page;
use Elfcms\Elfcms\Services\DynamicPageRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

$adminPath = config('elfcms.elfcms.admin_path') ?? 'admin';
$adminPath = trim($adminPath, '/');

Route::group(['middleware' => ['web', 'locales', 'cookie']], function () use ($adminPath) {

    Route::get('/maintenance', Elfcms\Elfcms\Http\Controllers\MaintenanceController::class)->name('maintenance');

    /* ---- Cookie consent ---- */

    Route::post('/cookie-consent', Elfcms\Elfcms\Http\Controllers\CookieConsentController::class)->name('cookie-consent');

    /* ------------------------ */

    /* Admin panel */
    Route::prefix($adminPath)->name('admin.')->middleware(['admin', 'access'])->group(function () use ($adminPath) {

        Route::get('/', [AdminController::class, 'index'])
            ->name('index');
        Route::get('/login', [LoginController::class, 'index'])
            ->name('login');
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('/forgot-password', [LoginController::class, 'getRestoreForm'])->name('getrestore');
        Route::post('/forgot-password', [LoginController::class, 'getRestore']);

        Route::get('/reset-password/{token}', [LoginController::class, 'setRestoreForm'])->name('setrestore');
        Route::post('/reset-password', [LoginController::class, 'setRestore'])->name('setnewpassword');

        /* ------------------------ */

        Route::name('settings.')->group(function () {
            Route::get('/settings', [SettingController::class, 'index'])->name('index');
            Route::post('/settings', [SettingController::class, 'save'])->name('save');
        });

        Route::name('cookie-settings.')->group(function () {
            Route::get('/cookie-settings', [CookieSettingController::class, 'index'])->name('index');
            Route::post('/cookie-settings', [CookieSettingController::class, 'save'])->name('save');
        });


        Route::prefix('user')->name('user.')->group(function () {
            Route::resource('/roles', Elfcms\Elfcms\Http\Controllers\Resources\RoleController::class)->names([
                'index' => 'roles',
                'create' => 'roles.create',
                'edit' => 'roles.edit',
                'store' => 'roles.store',
                'show' => 'roles.show',
                'edit' => 'roles.edit',
                'update' => 'roles.update',
                'destroy' => 'roles.destroy'
            ]);
            Route::resource('/users', Elfcms\Elfcms\Http\Controllers\Resources\UserController::class)->names([
                'index' => 'users',
                'create' => 'users.create',
                'edit' => 'users.edit',
                'store' => 'users.store',
                'show' => 'users.show',
                'edit' => 'users.edit',
                'update' => 'users.update',
                'destroy' => 'users.destroy'
            ]);
        });

        Route::prefix('email')->name('email.')->group(function () {
            Route::resource('/addresses', Elfcms\Elfcms\Http\Controllers\Resources\EmailAddressController::class)->names(['index' => 'addresses']);
            Route::resource('/events', Elfcms\Elfcms\Http\Controllers\Resources\EmailEventController::class)->names(['index' => 'events']);
        });

        Route::name('forms.')->group(function () {
            Route::resource('/forms', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::resource('/forms/{form}/groups', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldGroupController::class)->names(['index' => 'groups']);
            Route::resource('/forms/{form}/fields', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldController::class)
                ->names(['index' => 'fields']);
        });

        Route::name('form-results.')->group(function () {
            Route::get('/form-results', [FormResultController::class, 'index'])->name('index');
            Route::get('/form-results/{form}', [FormResultController::class, 'form'])->name('form');
            Route::get('/form-results/{form}/{result}', [FormResultController::class, 'show'])->name('show');
        });

        Route::name('menus.')->group(function () {
            Route::resource('/menus', Elfcms\Elfcms\Http\Controllers\Resources\MenuController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::resource('/menus/{menu}/items', Elfcms\Elfcms\Http\Controllers\Resources\MenuItemController::class)->names([
                'index' => 'items',
                'create' => 'items.create',
                'edit'   => 'items.edit',
                'store' => 'items.store',
                'show' => 'items.show',
                'update' => 'items.update',
                'destroy' => 'items.destroy'
            ]);
        });

        Route::prefix('page')->name('page.')->group(function () {
            Route::resource('/pages', Elfcms\Elfcms\Http\Controllers\Resources\PageController::class)->names(['index' => 'pages']);
            Route::get('/module-options/{module}', [Elfcms\Elfcms\Http\Controllers\Resources\PageController::class, 'getModuleOptions'])->name('module-options');

        });

        Route::name('messages.')->group(function () {
            Route::resource('/messages', Elfcms\Elfcms\Http\Controllers\Resources\MessageController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
        });
        Route::prefix('fragment')->name('fragment.')->group(function () {
            Route::resource('/items', \Elfcms\Elfcms\Http\Controllers\Resources\FragmentItemController::class)->names(['index' => 'items']);
        });

        Route::prefix('statistics')->name('statistics.')->group(function () {
            Route::get('/', [Elfcms\Elfcms\Http\Controllers\VisitStatisticController::class, 'index'])->name('index');
        });

        Route::prefix('backup')->name('backup.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::get('/settings', [BackupController::class, 'settings'])->name('settings');
            Route::post('/settings', [BackupController::class, 'settingsSave'])->name('settingsSave');
            Route::get('/download/{backup}', [BackupController::class, 'download'])->name('download');
            Route::delete('/delete/{backup}', [BackupController::class, 'delete'])->name('delete');
            Route::get('/restore/{backup}', [BackupController::class, 'restorePage'])->name('restore_page');
            Route::post('/restore/{backup}', [BackupController::class, 'restore'])->name('restore');
            Route::get('/restore/{backup}/result', [BackupController::class, 'restoreResult'])->name('restore_result');

            Route::post('/start', [BackupAjaxController::class, 'start'])->name('start');
            Route::get('/progress', [BackupAjaxController::class, 'progress'])->name('progress');
            Route::get('/result', [BackupAjaxController::class, 'result'])->name('result');
        });

        Route::prefix('system')->name('system.')->group(function () {
            Route::get('/', [SystemController::class, 'index'])->name('index');
            Route::get('/license', [AdminController::class, 'license'])->name('license');
            Route::get('/updates', [SystemController::class, 'updates'])
                ->name('updates');
            Route::post('/check-updates', [SystemController::class, 'checkUpdates'])
                ->name('checkUpdates');
            Route::post('/update/all', [SystemController::class, 'updateAll'])
                ->name('update-all');
            Route::post('/update/{module}', [SystemController::class, 'update'])
                ->name('update');

            Route::post('/install/{moduleName}', [SystemController::class, 'install'])->name('install.module');
        });

        Route::name('filestorage.')->group(function () {
            Route::resource('/filestorage/groups', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageFilegroupController::class);
            Route::resource('/filestorage/types', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageFiletypeController::class);

            Route::resource('/filestorage', \Elfcms\Elfcms\Http\Controllers\Resources\FilestorageController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   => 'edit',
                'store' => 'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);

            Route::resource('/filestorage/{filestorage}/files', Elfcms\Elfcms\Http\Controllers\Resources\FilestorageFileController::class)
                ->names([
                    'index' => 'files.index',
                    'create' => 'files.create',
                    'edit' => 'files.edit',
                    'store' => 'files.store',
                    'show' => 'files.show',
                    'edit' => 'files.edit',
                    'update' => 'files.update',
                    'destroy' => 'files.destroy',
                ]);
            Route::post('/filestorage/{filestorage}/files/group', [FilestorageFileController::class, 'filestorageFileGroupSave'])->name('files.groupSave');
        });

        // File icons
        Route::get('/helper/file-icon/{extension}', [AdminController::class, 'fileIcon'])->name('file-icon');
        Route::get('/helper/file-icon-data/{extension}', [AdminController::class, 'fileIconData'])->name('file-icon-data');

        Route::prefix('elfcms/api')->name('ajax.')->group(function () {

            Route::name('form.')->group(function () {
                Route::post('/form/{form}/grouporder', [Elfcms\Elfcms\Http\Controllers\Ajax\FormController::class, 'groupOrder']);
                Route::post('/form/{form}/fieldorder', [Elfcms\Elfcms\Http\Controllers\Ajax\FormController::class, 'fieldOrder']);
            });

            Route::name('menu.')->group(function () {
                Route::post('/menu/{menu}/itemorder', [Elfcms\Elfcms\Http\Controllers\Ajax\MenuController::class, 'itemOrder']);
            });

            Route::name('fragment.')->group(function () {
                Route::get('/fragment/datatypes', [Elfcms\Elfcms\Http\Controllers\Ajax\FragmentDataTypeController::class, 'get']);
            });

            Route::name('csrf.')->group(function () {
                Route::get('/csrf', [Elfcms\Elfcms\Http\Controllers\Ajax\CSRFController::class, 'get']);
            });

            Route::prefix('filestorage')->name('filestorage.')->group(function () {
                Route::name('group.')->group(function () {
                    Route::get('/group/list/{byId?}', [FilestorageFilegroupController::class, 'list'])->name('list');
                    Route::get('/group/empty-group', [FilestorageFilegroupController::class, 'emptyItem'])->name('empty-item');
                    Route::post('/group/fullsave', [FilestorageFilegroupController::class, 'save'])->name('fullsave');
                });
                Route::name('type.')->group(function () {
                    Route::get('/type/list/{byId?}', [FilestorageFiletypeController::class, 'list'])->name('list');
                    Route::get('/type/empty-type', [FilestorageFiletypeController::class, 'emptyItem'])->name('empty-item');
                    Route::post('/type/fullsave', [FilestorageFiletypeController::class, 'save'])->name('fullsave');
                });
                Route::post('/{filestorage}/files/group', [FilestorageFileController::class, 'filestorageFileGroupSave'])->name('filestorage.files.groupSave');
            });
        });
        /* JS admin const */
        Route::get('/js/system.js', function () use ($adminPath) {
            $content = "var adminPath = '/$adminPath';";
            header('Content-Type: text/javascript');
            return $content;
        });
        Route::get('/ajax/json/lang/{name}', function (Request $request, $name) {
            $result = [];
            if ($request->ajax()) {
                if (Lang::has('elfcms::' . $name)) {
                    $result = Lang::get('elfcms::' . $name);
                }
            }
            return json_encode($result);
        });
        /* /JS admin const */
    });
    /* /Admin panel */

    /* File Storage */

    Route::get('/files/preview/{file?}', [FilestorageFile::class, 'preview'])
        ->where('file', '.*')
        ->name('files.preview');
    Route::get('/files/{file}', [FilestorageFile::class, 'show'])
        ->where('file', '.*')
        ->name('files');

    /* /File Storage */

    /* Form processing */
    Route::post('/form/send', [FormSendController::class, 'send'])->name('form-send');
    Route::get('/form/result', [FormSendController::class, 'result'])->name('form-result');
    /* /Form processing */

    /* Public */

    /*  User defined pages */
    Route::get(config('elfcms.elfcms.page_path') . '/{page:slug}', [PageController::class, 'get']);

    try {
        if (Schema::hasTable('pages')) {
            $pages = Page::where('active', 1)->whereNotNull('path')->get();
            foreach ($pages as $page) {
                if (!empty($page->module) && $page->module != 'standard') {
                    return DynamicPageRouter::moduleRoutes($page);
                }
                elseif (boolval($page->is_dynamic)) {
                    Route::get($page->path, function () use ($page) {
                        $controller = new PageController;
                        $template = $page->template;
                        if (empty($template)) {
                            $template = 'default';
                        }
                        return $controller->get($page, dynamic: false, template: $template);
                    });
                }
            }
        }
    } catch (\Exception $e) {
        //
    }
});
