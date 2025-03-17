<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Role;
use Elfcms\Elfcms\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminController extends Controller
{
    public function index()
    {
        $configs = config('elfcms');
        //$menuData = $configs['elfcms']['menu'];
        $menuData = [];
        foreach ($configs as $modName => $module) {
            if (!empty($module['menu'])) {
                foreach ($module['menu'] as $key => $data) {
                    if ($data['route'] == Route::currentRouteName() || $data['route'] == 'admin.system.index') { //'admin.index') {
                        //unset($menuData[$key]);
                        continue;
                    }
                    $subdata = [];
                    $text = '';
                    if ($data['route'] == 'admin.user.users' && !empty($data['submenu'])) {
                        foreach ($data['submenu'] as $submenu) {
                            if ($submenu['route'] == 'admin.user.users') {
                                $usersCount = User::count();
                                $inactiveUserCount = User::where('is_confirmed', '<>', 1)->count();
                                $subdata['users'] = [
                                    'title' => __('elfcms::default.users'),
                                    'value' => $usersCount . ' (' . $inactiveUserCount . ' ' . __('elfcms::default.inactive') . ')'
                                ];
                            }
                            if ($submenu['route'] == 'admin.user.roles') {
                                $rolesCount = Role::count();
                                $subdata['roles'] = [
                                    'title' => __('elfcms::default.roles'),
                                    'value' => $rolesCount
                                ];
                            }
                        }
                    }

                    if ($data['route'] == 'admin.settings.index') {
                        $text = __('elfcms::default.basic_settings_for_your_site');
                    }
                    if ($data['route'] == 'admin.page.pages') {
                        $text = __('elfcms::default.static_pages_of_your_site');
                    }
                    $menuData[$modName.$key] = $data;
                    $menuData[$modName.$key]['subdata'] = $subdata;
                    $menuData[$modName.$key]['text'] = $text;
                    $langTitle = __($data['lang_title']);
                    $menuData[$modName.$key]['title'] = $langTitle == $data['lang_title'] ? $data['title'] : $langTitle;
                }
            }
        }

        return view('elfcms::admin.index', [
            'page' => [
                'title' => __('elfcms::default.administration_panel'),
                'current' => url()->current(),
            ],
            'menuData' => $menuData,
        ]);
    }

    public function users()
    {
        return view('elfcms::admin.user.users.index', [
            'page' => [
                'title' => 'Users',
                'current' => url()->current(),
            ],
            'currentUser' => Auth::user()
        ]);
    }

    public function settings()
    {
        return view('elfcms::admin.settings.index', [
            'page' => [
                'title' => 'Settings',
                'current' => url()->current(),
            ]
        ]);
    }

    public function form()
    {
        return view('elfcms::admin.forms.index', [
            'page' => [
                'title' => 'Forms',
                'current' => url()->current(),
            ]
        ]);
    }

    public function email()
    {
        return view('elfcms::admin.email.index', [
            'page' => [
                'title' => 'Email',
                'current' => url()->current(),
            ]
        ]);
    }

    /* public function login()
    {
        if (Auth::check()) {
            return redirect(route('admin.index'));
        }
        return view('elfcms::admin.login',[
            'page' => [
                'title' => 'Login',
                'current' => url()->current(),
            ]
        ]);
    } */

    public function fileIcon($extension)
    {
        return fsIcon($extension);
    }

    public function fileIconData($extension)
    {
        $file = public_path(fsIcon($extension));
        if (!file_exists($file)) {
            $file = public_path('elfcms/admin/images/icons/filestorage/any.svg');
        }
        return response()->file($file);
    }

    public function license()
    {
        $text = "The MIT License (MIT)\r\n\r\n Copyright (c) 2023 Maxim Klassen\r\n\r\n Permission is hereby granted, free of charge, to any person obtaining a copy\r\n of this software and associated documentation files (the \"Software\"), to deal\r\n in the Software without restriction, including without limitation the rights\r\n to use, copy, modify, merge, publish, distribute, sublicense, and/or sell\r\n copies of the Software, and to permit persons to whom the Software is\r\n furnished to do so, subject to the following conditions:\r\n \r\n The above copyright notice and this permission notice shall be included in\r\n all copies or substantial portions of the Software.\r\n \r\n THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR\r\n IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,\r\n FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE\r\n AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER\r\n LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,\r\n OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN\r\n THE SOFTWARE.";
        $file = base_path('vendor/elfcms/elfcms/LICENSE');
        if (!file_exists($file)) {
            $file = base_path('packages/elfcms/elfcms/LICENSE');
        }
        if (file_exists($file)) {
            $text = file_get_contents($file);
        }

        return view('elfcms::admin.license.index', [
            'page' => [
                'title' => __('elfcms::default.license'),
                'current' => url()->current(),
            ],
            'text' => nl2br($text),
        ]);
    }
}
