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
    /* public function index()
    {

        return view('elfcms::admin.index',[
            'page' => [
                'title' => 'ELF CMS',
                'current' => url()->current(),
            ],
            //'menuData' => $menuData,
        ]);
    } */
    public function index()
    {
        $menuData = config('elfcms.elfcms.menu');
        foreach ($menuData as $key => $data) {
            if ($data['route'] == Route::currentRouteName()) { //'admin.index') {
                unset($menuData[$key]);
                continue;
            }
            $subdata = [];
            $text = '';
            if ($data['route'] == 'admin.user.users' && !empty($data['submenu'])) {
                foreach ($data['submenu'] as $submenu) {
                    if ($submenu['route'] == 'admin.user.users') {
                        $usersCount = User::count();
                        $inactiveUserCount = User::where('is_confirmed','<>',1)->count();
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

            if ($data['route'] == 'admin.settings.index')  {
                $text = __('elfcms::default.basic_settings_for_your_site');
            }
            if ($data['route'] == 'admin.page.pages')  {
                $text = __('elfcms::default.static_pages_of_your_site');
            }

            $menuData[$key]['subdata'] = $subdata;
            $menuData[$key]['text'] = $text;
            $langTitle = __($data['lang_title']);
            $menuData[$key]['title'] = $langTitle == $data['lang_title'] ? $data['title'] : $langTitle;
        }

        return view('elfcms::admin.index',[
            'page' => [
                'title' => __('elfcms::default.administration_panel'),
                'current' => url()->current(),
            ],
            'menuData' => $menuData,
        ]);

    }

    public function users()
    {
        return view('elfcms::admin.user.users.index',[
            'page' => [
                'title' => 'Users',
                'current' => url()->current(),
            ],
            'currentUser' => Auth::user()
        ]);
    }

    public function settings()
    {
        return view('elfcms::admin.settings.index',[
            'page' => [
                'title' => 'Settings',
                'current' => url()->current(),
            ]
        ]);
    }

    public function form()
    {
        return view('elfcms::admin.forms.index',[
            'page' => [
                'title' => 'Forms',
                'current' => url()->current(),
            ]
        ]);
    }

    public function email()
    {
        return view('elfcms::admin.email.index',[
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

    public function fileIcon($extension) {
        return fsIcon($extension);
    }

}
