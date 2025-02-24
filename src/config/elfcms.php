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

    'module_name' => 'elfcms',
    'module_title' => 'Basic',
    'version' => '3.0',
    'release_status' => 'dev',
    'release_date' => date('Y-m-d'),

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
            'root' => storage_path('app/elfcms/packages/filestorage'),
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
    | File storage
    |--------------------------------------------------------------------------
    |
    | File storage settings
    |
    */

    'filestorage' => [
        'disk' => 'filestorage',
        'path' => 'files',
        'max_size' => 1024 * 1024 * 10,
        'types' => [
            'image' => [
                'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
                'max_size' => 1024 * 1024 * 5,
                'width' => 1920,
                'height' => 1080,
                'quality' => 80,
            ],
            'document' => [
                'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
                'max_size' => 1024 * 1024 * 10,
            ],
            'archive' => [
                'extensions' => ['zip', 'rar', '7z', 'tar', 'gz'],
                'max_size' => 1024 * 1024 * 20,
            ],
            'audio' => [
                'extensions' => ['mp3', 'wav', 'ogg'],
                'max_size' => 1024 * 1024 * 10,
            ],
            'video' => [
                'extensions' => ['mp4', 'avi', 'mov', 'mkv'],
                'max_size' => 1024 * 1024 * 50,
            ],
        ],
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
            "title" => "Start",
            "lang_title" => "elfcms::default.start",
            "route" => "admin.index",
            "parent_route" => "admin.index",
            "icon" => "/elfcms/admin/images/icons/home.svg",
            "color" => "var(--purple-color)",
            "position" => 1
        ],
        [
            "title" => "Settings",
            "lang_title" => "elfcms::default.settings",
            "route" => "admin.settings.index",
            "parent_route" => "admin.settings.index",
            "icon" => "/elfcms/admin/images/icons/settings.svg",
            "color" => "var(--lilac-color)",
            "position" => 10
        ],
        [
            "title" => "Users & Roles",
            "lang_title" => "elfcms::default.users_n_roles",
            "route" => "admin.user.users",
            "parent_route" => "admin.user",
            "icon" => "/elfcms/admin/images/icons/users.svg",
            "color" => "var(--green-color)",
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
            "icon" => "/elfcms/admin/images/icons/email_addresses.svg",
            "color" => "var(--cyan-color)",
            "position" => 30,
        ],
        [
            "title" => "Email events",
            "lang_title" => "elfcms::default.email_events",
            "route" => "admin.email.events",
            "parent_route" => "admin.email.events",
            "icon" => "/elfcms/admin/images/icons/email_events.svg",
            "color" => "var(--blue-color)",
            "position" => 35,
        ],
        [
            "title" => "Form",
            "lang_title" => "elfcms::default.form",
            "route" => "admin.forms.index",
            "parent_route" => "admin.forms",
            "icon" => "/elfcms/admin/images/icons/form.svg",
            "color" => "var(--gold-color)",
            "position" => 40,
        ],
        [
            "title" => "Form results",
            "lang_title" => "elfcms::default.form_results",
            "route" => "admin.form-results.index",
            "parent_route" => "admin.form-results",
            "icon" => "/elfcms/admin/images/icons/formresult.svg",
            "color" => "var(--orange-color)",
            "position" => 45,
        ],
        [
            "title" => "Menu",
            "lang_title" => "elfcms::default.menu",
            "route" => "admin.menus.index",
            "parent_route" => "admin.menus",
            "icon" => "/elfcms/admin/images/icons/menu.svg",
            "color" => "var(--purple-color)",
            "position" => 50,
        ],
        [
            "title" => "Pages",
            "lang_title" => "elfcms::default.pages",
            "route" => "admin.page.pages",
            "parent_route" => "admin.page",
            "icon" => "/elfcms/admin/images/icons/pages.svg",
            "color" => "var(--pink-color)",
            "position" => 60,
        ],
        /* [
            "title" => "Messages",
            "lang_title" => "elfcms::default.messages",
            "route" => "admin.messages.index",
            "parent_route" => "admin.messages",
            "icon" => "/elfcms/admin/images/icons/info.svg",
            "color" => "var(--blue-color)",
            "position" => 70,
        ], */
        [
            "title" => "Fragment",
            "lang_title" => "elfcms::default.fragment",
            "route" => "admin.fragment.items",
            "parent_route" => "admin.fragment.items",
            "icon" => "/elfcms/admin/images/icons/fragment.svg",
            "color" => "var(--marin-color)",
            "position" => 80,
        ],
        [
            "title" => "FileStorage",
            "lang_title" => "elfcms::default.filestorage",
            "route" => "admin.filestorage.index",
            "parent_route" => "admin.filestorage",
            "icon" => "/elfcms/admin/images/icons/filestorage.svg",
            "color" => "var(--lime-color)",
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
            "icon" => "/elfcms/admin/images/icons/cookie.svg",
            "color" => "var(--yellow-color)",
            "position" => 90,
        ],
        [
            "title" => "Statistics",
            "lang_title" => "elfcms::default.statistics",
            "route" => "admin.statistics.index",
            "parent_route" => "admin.statistics.index",
            "icon" => "/elfcms/admin/images/icons/stats.svg",
            "color" => "var(--red-color)",
            "position" => 100,
        ],
        [
            "title" => "System",
            "lang_title" => "elfcms::default.system",
            "route" => "admin.system.index",
            "parent_route" => "admin.system.index",
            "icon" => "/elfcms/admin/images/logo/logo-outline-color-let.svg",
            "color" => "var(--text-color-default)",
            "position" => 110,
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
            "title" => "Start",
            "lang_title" => "elfcms::default.start",
            "route" => "admin.index",
            //"actions" => ["read", "write"],
        ],
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
        ],
        [
            "title" => "System",
            "lang_title" => "elfcms::default.system",
            "route" => "admin.system",
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
