<?php
return [
    'accessBackend' => [
        'type' => 2,
        'description' => 'Can access backend',
    ],
    'manager' => [
        'type' => 1,
        'description' => 'Manager',
        'children' => [
            'accessBackend',
            'manageUser',
            'viewUser',
            'updateOwnUser',
            'managePost',
            'viewPost',
            'updatePost',
            'createPost',
            'manageCategory',
            'viewCategory',
            'updateCategory',
            'createCategory',
            'managePage',
            'viewPage',
            'updatePage',
            'createPage',
            'manageMenu',
            'viewMenu',
            'updateMenu',
            'createMenu',
            'manageMenuType',
            'viewMenuType',
            'updateMenuType',
            'createMenuType',
            'manageLanguage',
            'viewLanguage',
            'updateLanguage',
            'createLanguage',
            'manageSettings',
            'viewSettings',
            'updateSettings',
            'createSettings',
            'manageAlbum',
            'viewAlbum',
            'updateAlbum',
            'createAlbum',
            'managePhoto',
            'viewPhoto',
            'updatePhoto',
            'createPhoto',
            'manageDashboard',
            'manageFilemanager',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'manager',
            'createUser',
            'updateUser',
            'deleteOwnUser',
            'deletePost',
            'deleteCategory',
            'deletePage',
            'deleteMenu',
            'deleteMenuType',
            'deleteLanguage',
            'deleteSettings',
            'deleteAlbum',
            'deletePhoto',
        ],
    ],
    'superadmin' => [
        'type' => 1,
        'description' => 'Super admin',
        'children' => [
            'admin',
        ],
    ],
    'manageUser' => [
        'type' => 2,
        'description' => 'Manage users',
    ],
    'viewUser' => [
        'type' => 2,
        'description' => 'View user',
    ],
    'createUser' => [
        'type' => 2,
        'description' => 'Create user',
    ],
    'updateUser' => [
        'type' => 2,
        'description' => 'Update user',
    ],
    'updateOwnUser' => [
        'type' => 2,
        'description' => 'Update own user profile',
        'ruleName' => 'isAuthor',
        'children' => [
            'updateUser',
        ],
    ],
    'deleteUser' => [
        'type' => 2,
        'description' => 'Delete user',
    ],
    'deleteOwnUser' => [
        'type' => 2,
        'description' => 'Delete own user profile',
        'ruleName' => 'isReverseAuthor',
        'children' => [
            'deleteUser',
        ],
    ],
    'managePost' => [
        'type' => 2,
        'description' => 'Manage posts',
    ],
    'viewPost' => [
        'type' => 2,
        'description' => 'View post',
    ],
    'createPost' => [
        'type' => 2,
        'description' => 'Create post',
    ],
    'updatePost' => [
        'type' => 2,
        'description' => 'Update post',
    ],
    'updateOwnPost' => [
        'type' => 2,
        'description' => 'Update own post',
        'ruleName' => 'isAuthor',
        'children' => [
            'updatePost',
        ],
    ],
    'deletePost' => [
        'type' => 2,
        'description' => 'Delete post',
    ],
    'deleteOwnPost' => [
        'type' => 2,
        'description' => 'Delete own post',
        'ruleName' => 'isAuthor',
        'children' => [
            'deletePost',
        ],
    ],
    'manageCategory' => [
        'type' => 2,
        'description' => 'Manage categories',
    ],
    'viewCategory' => [
        'type' => 2,
        'description' => 'View category',
    ],
    'createCategory' => [
        'type' => 2,
        'description' => 'Create category',
    ],
    'updateCategory' => [
        'type' => 2,
        'description' => 'Update category',
    ],
    'updateOwnCategory' => [
        'type' => 2,
        'description' => 'Update own category',
        'ruleName' => 'isAuthor',
        'children' => [
            'updateCategory',
        ],
    ],
    'deleteCategory' => [
        'type' => 2,
        'description' => 'Delete category',
    ],
    'deleteOwnCategory' => [
        'type' => 2,
        'description' => 'Delete own category',
        'ruleName' => 'isAuthor',
        'children' => [
            'deleteCategory',
        ],
    ],
    'managePage' => [
        'type' => 2,
        'description' => 'Manage pages',
    ],
    'viewPage' => [
        'type' => 2,
        'description' => 'View page',
    ],
    'createPage' => [
        'type' => 2,
        'description' => 'Create page',
    ],
    'updatePage' => [
        'type' => 2,
        'description' => 'Update page',
    ],
    'updateOwnPage' => [
        'type' => 2,
        'description' => 'Update own page',
        'ruleName' => 'isAuthor',
        'children' => [
            'updatePage',
        ],
    ],
    'deletePage' => [
        'type' => 2,
        'description' => 'Delete page',
    ],
    'deleteOwnPage' => [
        'type' => 2,
        'description' => 'Delete own page',
        'ruleName' => 'isAuthor',
        'children' => [
            'deletePage',
        ],
    ],
    'manageMenu' => [
        'type' => 2,
        'description' => 'Manage menus',
    ],
    'viewMenu' => [
        'type' => 2,
        'description' => 'View menu',
    ],
    'createMenu' => [
        'type' => 2,
        'description' => 'Create menu',
    ],
    'updateMenu' => [
        'type' => 2,
        'description' => 'Update menu',
    ],
    'deleteMenu' => [
        'type' => 2,
        'description' => 'Delete menu',
    ],
    'manageMenuType' => [
        'type' => 2,
        'description' => 'Manage menu types',
    ],
    'viewMenuType' => [
        'type' => 2,
        'description' => 'View menu type',
    ],
    'createMenuType' => [
        'type' => 2,
        'description' => 'Create menu type',
    ],
    'updateMenuType' => [
        'type' => 2,
        'description' => 'Update menu type',
    ],
    'deleteMenuType' => [
        'type' => 2,
        'description' => 'Delete menu type',
    ],
    'manageLanguage' => [
        'type' => 2,
        'description' => 'Manage languages',
    ],
    'viewLanguage' => [
        'type' => 2,
        'description' => 'View language',
    ],
    'createLanguage' => [
        'type' => 2,
        'description' => 'Create language',
    ],
    'updateLanguage' => [
        'type' => 2,
        'description' => 'Update language',
    ],
    'deleteLanguage' => [
        'type' => 2,
        'description' => 'Delete language',
    ],
    'manageSettings' => [
        'type' => 2,
        'description' => 'Manage settings',
    ],
    'viewSettings' => [
        'type' => 2,
        'description' => 'View settings',
    ],
    'createSettings' => [
        'type' => 2,
        'description' => 'Create settings',
    ],
    'updateSettings' => [
        'type' => 2,
        'description' => 'Update settings',
    ],
    'deleteSettings' => [
        'type' => 2,
        'description' => 'Delete settings',
    ],
    'manageAlbum' => [
        'type' => 2,
        'description' => 'Manage album',
    ],
    'viewAlbum' => [
        'type' => 2,
        'description' => 'View album',
    ],
    'createAlbum' => [
        'type' => 2,
        'description' => 'Create album',
    ],
    'updateAlbum' => [
        'type' => 2,
        'description' => 'Update album',
    ],
    'deleteAlbum' => [
        'type' => 2,
        'description' => 'Delete album',
    ],
    'managePhoto' => [
        'type' => 2,
        'description' => 'Manage photos',
    ],
    'viewPhoto' => [
        'type' => 2,
        'description' => 'View photo',
    ],
    'createPhoto' => [
        'type' => 2,
        'description' => 'Create photo',
    ],
    'updatePhoto' => [
        'type' => 2,
        'description' => 'Update photo',
    ],
    'deletePhoto' => [
        'type' => 2,
        'description' => 'Delete photo',
    ],
    'manageDashboard' => [
        'type' => 2,
        'description' => 'Manage dashboard',
    ],
    'manageFilemanager' => [
        'type' => 2,
        'description' => 'Manage dashboard',
    ],
];
