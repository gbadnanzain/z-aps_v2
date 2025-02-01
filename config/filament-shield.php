<?php

return [
    'panel' => [
        'shield_resource' => [
            'enabled' => true,  // Pastikan Shield diaktifkan
            'group' => 'Settings',
            'sort' => null,
            'icon' => 'heroicon-o-shield-check',
            'visible' => true, // Jangan pakai Auth::user() di sini!
        ],
    ],
    'shield_resource' => [
        'should_register_navigation' => true,
        'slug' => 'shield/roles',
        'navigation_sort' => -1,
        'navigation_badge' => true,
        'navigation_group' => true,
        'is_globally_searchable' => false,
        'show_model_path' => true,
        'is_scoped_to_tenant' => true,
        'cluster' => null,
    ],
    'permissions' => [
        'admin' => [
            'access_dashboard' => true,
            'view_users' => true,
            'view_transactions' => true,
            // Add more permissions here as needed
        ],
        'super-admin' => [
            'access_dashboard' => true,
            'view_users' => true,
            'view_transactions' => true,
            // Add more permissions here as needed
        ],
        // other roles' permissions...
    ],
    'tenant_model' => null,

    'auth_provider_model' => [
        'fqcn' => 'App\\Models\\User',
    ],

    'super_admin' => [
        'enabled' => true,
        'name' => 'super_admin',
        'define_via_gate' => false,
        'intercept_gate' => 'before', // after
    ],

    'panel_user' => [
        'enabled' => true,
        'name' => 'panel_user',
    ],

    'permission_prefixes' => [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
        ],

        'page' => 'page',
        'widget' => 'widget',
    ],

    'entities' => [
        'pages' => true,
        'widgets' => true,
        'resources' => true,
        'custom_permissions' => true,
    ],

    'generator' => [
        'option' => 'policies_and_permissions',
        'policy_directory' => 'Policies',
        'policy_namespace' => 'Policies',
    ],

    'exclude' => [
        'enabled' => true,

        'pages' => [
            'Dashboard',
        ],

        'widgets' => [
            //'AccountWidget', 'FilamentInfoWidget',
            'AccountWidget',
            'SoTakeIdListWidget'
        ],

        'resources' => [],
    ],

    'discovery' => [
        'discover_all_resources' => true,
        'discover_all_widgets' => true,
        'discover_all_pages' => true,
    ],

    'register_role_policy' => [
        'enabled' => true,
    ],

];
