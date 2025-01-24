<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    |
    | Name of package
    |
    */

    'name' => 'Elfcms',
    'title' => 'ELF CMS',

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version of package
    |
    */

    'version' => '2.2',

    /*
    |--------------------------------------------------------------------------
    | Version is beta
    |--------------------------------------------------------------------------
    */

    'is_beta' => true,

    /*
    |--------------------------------------------------------------------------
    | Email confirmation
    |--------------------------------------------------------------------------
    |
    | This option determines whether confirmation of the email address
    | is required upon registration (true/false)
    |
    */

    'email_confirmation' => true,

    /*
    |--------------------------------------------------------------------------
    | Confirmation period
    |--------------------------------------------------------------------------
    |
    | This option determines validity period of
    | the confirmation token (seconds, integer)
    |
    */

    'confirmation_period' => 86400,

    /*
    |--------------------------------------------------------------------------
    | Minimum length of password
    |--------------------------------------------------------------------------
    |
    | integer
    |
    */

    'password_length' => 8,

    /*
    |--------------------------------------------------------------------------
    | Default user role
    |--------------------------------------------------------------------------
    |
    | Default role for registered users
    |
    */

    'user_default_role' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Admin user role
    |--------------------------------------------------------------------------
    |
    | Admin role
    |
    */

    'user_admin_role' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Admin page path
    |--------------------------------------------------------------------------
    */

    'admin_path' => '/admin',

    /*
    |--------------------------------------------------------------------------
    | Dinamic page path
    |--------------------------------------------------------------------------
    */

    'page_path' => '/page',

    /*
    |--------------------------------------------------------------------------
    | View disk
    |--------------------------------------------------------------------------
    |
    | Disk of views for listing
    |
    */

    'disks' => [

        'elfcmsviews' => [
            'driver' => 'local',
            'root' => base_path('vendor/elfcms/elfcms/src/resources/views'),
        ],
        'publicdata' => [
            'driver' => 'local',
            'root' => public_path('data'),
        ],
        'publicviews' => [
            'driver' => 'local',
            'root' => base_path('resources/views'),
        ],
        'elfcmsdev' => [
            'driver' => 'local',
            'root' => base_path('packages/elfcms/elfcms/src'),
        ],
        'filestorage' => [
            'driver' => 'local',
            'root' => storage_path('paappckages/elfcms/filestorage'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Locales for project
    |
    */

    'locales' => [
        [
            'code' => 'en',
            'name' => 'English'
        ],
        [
            'code' => 'de',
            'name' => 'Deutsch'
        ],
        [
            'code' => 'ru',
            'name' => 'Русский'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu data
    |--------------------------------------------------------------------------
    |
    | Menu data of this package for admin panel
    |
    */

    "menu" => [
        [
            "title" => "Settings",
            "lang_title" => "elfcms::default.settings",
            "route" => "admin.settings.index",
            "parent_route" => "admin.settings.index",
            "icon" => "/elfcms/admin/images/icons/settings.png",
            "position" => 10
        ],
        [
            "title" => "Users",
            "lang_title" => "elfcms::default.users",
            "route" => "admin.user.users",
            "parent_route" => "admin.user",
            "icon" => "/elfcms/admin/images/icons/users.png",
            "position" => 20,
            "submenu" => [
                [
                    "title" => "Users",
                    "lang_title" => "elfcms::default.users",
                    "route" => "admin.user.users"
                ],
                [
                    "title" => "Roles",
                    "lang_title" => "elfcms::default.roles",
                    "route" => "admin.user.roles"
                ],
            ]
        ],
        [
            "title" => "Email addresses",
            "lang_title" => "elfcms::default.email_addresses",
            "route" => "admin.email.addresses",
            "parent_route" => "admin.email.addresses",
            "icon" => "/elfcms/admin/images/icons/email.png",
            "position" => 30,
        ],
        [
            "title" => "Email events",
            "lang_title" => "elfcms::default.email_events",
            "route" => "admin.email.events",
            "parent_route" => "admin.email.events",
            "icon" => "/elfcms/admin/images/icons/event.png",
            "position" => 35,
        ],
        [
            "title" => "Form",
            "lang_title" => "elfcms::default.form",
            "route" => "admin.forms.index",
            "parent_route" => "admin.forms",
            "icon" => "/elfcms/admin/images/icons/forms.png",
            "position" => 40,
        ],
        [
            "title" => "Form results",
            "lang_title" => "elfcms::default.form_results",
            "route" => "admin.form-results.index",
            "parent_route" => "admin.form-results",
            "icon" => "/elfcms/admin/images/icons/formresult.png",
            "position" => 45,
        ],
        [
            "title" => "Menu",
            "lang_title" => "elfcms::default.menu",
            "route" => "admin.menus.index",
            "parent_route" => "admin.menus",
            "icon" => "/elfcms/admin/images/icons/menu.png",
            "position" => 50,
        ],
        [
            "title" => "Pages",
            "lang_title" => "elfcms::default.pages",
            "route" => "admin.page.pages",
            "parent_route" => "admin.page",
            "icon" => "/elfcms/admin/images/icons/pages.png",
            "position" => 60,
        ],
        [
            "title" => "Messages",
            "lang_title" => "elfcms::default.messages",
            "route" => "admin.messages.index",
            "parent_route" => "admin.messages",
            "icon" => "/elfcms/admin/images/icons/info.png",
            "position" => 70,
        ],
        [
            "title" => "Fragment",
            "lang_title" => "elfcms::default.fragment",
            "route" => "admin.fragment.items",
            "parent_route" => "admin.fragment.items",
            "icon" => "/elfcms/admin/images/icons/fragment.png",
            "position" => 80,
        ],
        [
            "title" => "FileStorage",
            "lang_title" => "elfcms::default.filestorage",
            "route" => "admin.filestorage.index",
            "parent_route" => "admin.filestorage",
            "icon" => "/elfcms/admin/images/icons/filestorage.png",
            "position" => 85,
            "submenu" => [
                [
                    "title" => "Storages",
                    "lang_title" => "elfcms::default.storages",
                    "route" => "admin.filestorage.index"
                ],
                [
                    "title" => "Groups",
                    "lang_title" => "elfcms::default.groups",
                    "route" => "admin.filestorage.groups.index"
                ],
                [
                    "title" => "Types",
                    "lang_title" => "elfcms::default.types",
                    "route" => "admin.filestorage.types.index"
                ],
            ]
        ],
        [
            "title" => "Cookies",
            "lang_title" => "elfcms::default.cookies",
            "route" => "admin.cookie-settings.index",
            "parent_route" => "admin.cookie-settings.index",
            "icon" => "/elfcms/admin/images/icons/cookie.png",
            "position" => 90,
        ],
        [
            "title" => "Statistics",
            "lang_title" => "elfcms::default.statistics",
            "route" => "admin.statistics.index",
            "parent_route" => "admin.statistics.index",
            "icon" => "/elfcms/admin/images/icons/stats.png",
            "position" => 100,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Access control
    |--------------------------------------------------------------------------
    |
    | Define access rules for admin panel pages.
    |
    */

    "access_routes" => [
        [
            "title" => "Settings",
            "lang_title" => "elfcms::default.settings",
            "route" => "admin.settings",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Users and roles",
            "lang_title" => "elfcms::default.users_and_roles",
            "route" => "admin.user",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Email addresses",
            "lang_title" => "elfcms::default.email_addresses",
            "route" => "admin.email.addresses",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Email events",
            "lang_title" => "elfcms::default.email_events",
            "route" => "admin.email.events",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Form",
            "lang_title" => "elfcms::default.form",
            "route" => "admin.forms",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Form results",
            "lang_title" => "elfcms::default.form_results",
            "route" => "admin.form-results",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Menu",
            "lang_title" => "elfcms::default.menu",
            "route" => "admin.menus",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Pages",
            "lang_title" => "elfcms::default.pages",
            "route" => "admin.page",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Messages",
            "lang_title" => "elfcms::default.messages",
            "route" => "admin.messages",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Fragment",
            "lang_title" => "elfcms::default.fragment",
            "route" => "admin.fragment",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "FileStorage",
            "lang_title" => "elfcms::default.filestorage",
            "route" => "admin.filestorage",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Statistics",
            "lang_title" => "elfcms::default.statistics",
            "route" => "admin.statistics",
            "actions" => ["read"],
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Search settings
    |--------------------------------------------------------------------------
    |
    | Settings for search module
    |
    */

    "search" => [
        "tables" => [
            [
                "model" => "Elfcms\Elfcms\Models\Page",
                "name" => "pages",
                "fields" => [
                    "title" => "elfcms::default.search_page_title",
                    "content" => "elfcms::default.search_page_content",
                ],
                "result" => "slug",
                "description" => "elfcms::default.search_page",
                "path" => "page"
            ],
        ]
    ],

    'components' => [
        'fragment' => [
            'class' => '\Elfcms\Elfcms\View\Components\Fragment',
            'options' => [
                'item' => false,
                'theme' => 'default',
            ],
        ],
        'form' => [
            'class' => '\Elfcms\Elfcms\View\Components\Form',
            'options' => [
                'form' => false,
                'theme' => 'default',
            ],
        ],
        'menu' => [
            'class' => '\Elfcms\Elfcms\View\Components\Menu',
            'options' => [
                'menu' => false,
                'theme' => 'default',
            ],
        ],
        'message' => [
            'class' => '\Elfcms\Elfcms\View\Components\Message',
            'options' => [
                'message' => false,
                'theme' => 'default',
            ],
        ],
    ],
];
