<?php

use Elfcms\Elfcms\Models\DataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

$adminPath = config('elfcms.basic.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie']],function() use ($adminPath) {

    /* Admin panel */
    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        Route::get($adminPath . '',[Elfcms\Elfcms\Http\Controllers\AdminController::class,'index'])
        ->name('index');
        Route::get($adminPath . '/login',[Elfcms\Elfcms\Http\Controllers\LoginController::class,'index'])
        ->name('login');
        Route::post($adminPath . '/login', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'login']);
        Route::get($adminPath . '/logout', function() use ($adminPath) {
            Auth::logout();
            return redirect($adminPath . '/login');
        })->name('logout');


        Route::get($adminPath . '/forgot-password', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'getRestoreForm'])->name('getrestore');
        Route::post($adminPath . '/forgot-password', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'getRestore']);

        Route::get($adminPath . '/reset-password/{token}', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'setRestoreForm'])->name('setrestore');
        Route::post($adminPath . '/reset-password', [Elfcms\Elfcms\Http\Controllers\LoginController::class, 'setRestore'])->name('setnewpassword');

        /* ------------------------ */



        Route::name('settings.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/settings',[Elfcms\Elfcms\Http\Controllers\SettingController::class,'index'])->name('index');
            Route::post($adminPath . '/settings',[Elfcms\Elfcms\Http\Controllers\SettingController::class,'save'])->name('save');
        });
        Route::resource($adminPath . '/users/roles', Elfcms\Elfcms\Http\Controllers\Resources\RoleController::class)->names([
            'index' => 'users.roles',
            'create' => 'users.roles.create',
            'edit' => 'users.roles.edit',
            'store' => 'users.roles.store',
            'show' => 'users.roles.show',
            'edit' => 'users.roles.edit',
            'update' => 'users.roles.update',
            'destroy' => 'users.roles.destroy'
        ]);
        Route::resource($adminPath . '/users', Elfcms\Elfcms\Http\Controllers\Resources\UserController::class)->names(['index' => 'users']);

        Route::get($adminPath . '/ajax/json/lang/{name}',function(Request $request ,$name){
            $result = [];
            if ($request->ajax()) {
                if (Lang::has('elfcms::'.$name)) {
                    $result = Lang::get('elfcms::'.$name);
                }
            }
            return json_encode($result);
        });


        Route::name('email.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/email/addresses', Elfcms\Elfcms\Http\Controllers\Resources\EmailAddressController::class)->names(['index' => 'addresses']);
            Route::resource($adminPath . '/email/events', Elfcms\Elfcms\Http\Controllers\Resources\EmailEventController::class)->names(['index' => 'events']);
            //Route::resource($adminPath . '/email/templates', Elfcms\Elfcms\Http\Controllers\Resources\EmailTemplateController::class)->names(['index' => 'templates']);
        });

        Route::name('form.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/form/forms', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names(['index' => 'forms']);
            Route::resource($adminPath . '/form/groups', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldGroupController::class)->names(['index' => 'groups']);
            Route::resource($adminPath . '/form/fields', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldController::class)->names(['index' => 'fields']);
            Route::resource($adminPath . '/form/options', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldOptionController::class)->names(['index' => 'options']);
            Route::resource($adminPath . '/form/field-types', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names(['index' => 'field-types']);
            Route::resource($adminPath . '/form/results', Elfcms\Elfcms\Http\Controllers\Resources\FormResultController::class)->names(['index' => 'results']);
        });

        Route::name('forms.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/forms', Elfcms\Elfcms\Http\Controllers\Resources\FormController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   =>'edit',
                'store'=>'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::resource($adminPath . '/forms/{form}/groups', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldGroupController::class)->names(['index' => 'groups']);
            Route::resource($adminPath . '/forms/{form}/fields', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldController::class)
            ->names(['index' => 'fields']);
            Route::resource($adminPath . '/forms/{form}/fields/{field}/options', Elfcms\Elfcms\Http\Controllers\Resources\FormFieldOptionController::class)->names(['index' => 'options']);
            Route::resource($adminPath . '/forms/{form}/results', Elfcms\Elfcms\Http\Controllers\Resources\FormResultController::class)->names(['index' => 'results']);
        });

        Route::name('menus.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/menus', Elfcms\Elfcms\Http\Controllers\Resources\MenuController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   =>'edit',
                'store'=>'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
            Route::resource($adminPath . '/menus/{menu}/items', Elfcms\Elfcms\Http\Controllers\Resources\MenuItemController::class)->names([
                'index' => 'items',
                'create' => 'items.create',
                'edit'   =>'items.edit',
                'store'=>'items.store',
                'show' => 'items.show',
                'update' => 'items.update',
                'destroy' => 'items.destroy'
            ]);
        });

        Route::name('page.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/page/pages', Elfcms\Elfcms\Http\Controllers\Resources\PageController::class)->names(['index' => 'pages']);
        });

        Route::name('messages.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/messages', Elfcms\Elfcms\Http\Controllers\Resources\MessageController::class)->names([
                'index' => 'index',
                'create' => 'create',
                'edit'   =>'edit',
                'store'=>'store',
                'show' => 'show',
                'update' => 'update',
                'destroy' => 'destroy'
            ]);
        });
        Route::name('fragment.')->group(function() use ($adminPath) {
            Route::resource($adminPath . '/fragment/items', \Elfcms\Elfcms\Http\Controllers\Resources\FragmentItemController::class)->names(['index' => 'items']);
        });

        Route::name('statistics.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/statistics', [Elfcms\Elfcms\Http\Controllers\VisitStatisticController::class,'index'])->name('index');
        });


        Route::name('ajax.')->group(function() {

            Route::name('form.')->group(function() {
                Route::post('/elfcms/api/form/{form}/grouporder', [Elfcms\Elfcms\Http\Controllers\Ajax\FormController::class, 'groupOrder']);
                Route::post('/elfcms/api/form/{form}/fieldorder', [Elfcms\Elfcms\Http\Controllers\Ajax\FormController::class, 'fieldOrder']);
            });

            Route::name('menu.')->group(function() {
                Route::post('/elfcms/api/menu/{menu}/itemorder', [Elfcms\Elfcms\Http\Controllers\Ajax\MenuController::class, 'itemOrder']);
            });

            Route::name('fragment.')->group(function() {
                Route::get('/elfcms/api/fragment/datatypes', [Elfcms\Elfcms\Http\Controllers\Ajax\FragmentDataTypeController::class, 'get']);
            });

        });

    });
    /* /Admin panel */

});
