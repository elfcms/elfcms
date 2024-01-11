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

    'version' => '1.1.0',

    /*
    |--------------------------------------------------------------------------
    | Version is beta
    |--------------------------------------------------------------------------
    */

    'is_beta' => false,

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
            'root' => base_path('resources/views/elfcms'),
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
            "title" => "Email",
            "lang_title" => "elfcms::default.email",
            "route" => "admin.email.addresses",
            "parent_route" => "admin.email",
            "icon" => "/elfcms/admin/images/icons/email.png",
            "position" => 30,
            "submenu" => [
                [
                    "title" => "Addresses",
                    "lang_title" => "elfcms::default.addresses",
                    "route" => "admin.email.addresses"
                ],
                [
                    "title" => "Events",
                    "lang_title" => "elfcms::default.events",
                    "route" => "admin.email.events"
                ],
            ]
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
            "title" => "Statistics",
            "lang_title" => "elfcms::default.statistics",
            "route" => "admin.statistics.index",
            "parent_route" => "admin.statistics.index",
            "icon" => "/elfcms/admin/images/icons/stats.png",
            "position" => 90,
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
            "title" => "Users",
            "lang_title" => "elfcms::default.users",
            "route" => "admin.user.users",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "User roles",
            "lang_title" => "elfcms::default.roles",
            "route" => "admin.user.roles",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Addresses",
            "lang_title" => "elfcms::default.addresses",
            "route" => "admin.email.addresses",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Events",
            "lang_title" => "elfcms::default.events",
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
];
