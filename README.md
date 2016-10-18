Parus
=============

Parus is a Content Management System application powered by [Yii 2](https://github.com/yiisoft/yii2).

## Minimal system requirements:

* PHP 5.6 or higher
* MySQL 5.5+
* Needed PHP modules
    * GD PHP Extension
    * PDO PHP Extension
    * INTL PHP Extension (not required but recommended)

## Installation and configuration

*If you do not have Composer-Asset-Plugin installed, you may install it by running command: `composer global require "fxp/composer-asset-plugin:1.2.0"`

1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/):
 * Run `composer require --prefer-dist rokorolov/parus "~1.0"` or add `"rokorolov/parus": "~1.0"` to the require section of your `composer.json` file.

* Add module to config section:
 * ` 'modules' => [
    'admin' => [
        'class' => 'rokorolov\parus\admin\Module'
    ]
]`

* Add aliase, user module and AuthManager component to console config:
 * ` 'modules' => [
    'user' => [
        'class' => 'rokorolov\parus\user\Module',
        'controllerNamespace' => 'rokorolov\parus\user\console\controllers'
    ]
]`
 * `'aliases' => [
        '@rokorolov/parus' => '@vendor/rokorolov/parus/src'
 ]`
 * ` 'authManager' => [
        'class' => 'rokorolov\parus\admin\components\AuthManager',
        'itemFile' => '@rokorolov/parus/admin/rbac/items.php',
        'ruleFile' => '@rokorolov/parus/admin/rbac/rules.php',
        'assignmentFile' => '@rokorolov/parus/admin/rbac/assignments.php'
]`

* Run migrations:
 - `php yii migrate --migrationPath=@rokorolov/parus/language/migrations`
 - `php yii migrate --migrationPath=@rokorolov/parus/user/migrations`
 - `php yii migrate --migrationPath=@rokorolov/parus/settings/migrations`
 - `php yii migrate --migrationPath=@rokorolov/parus/blog/migrations`
 - `php yii migrate --migrationPath=@rokorolov/parus/page/migrations`
 - `php yii migrate --migrationPath=@rokorolov/parus/menu/migrations`
 - `php yii migrate --migrationPath=@rokorolov/parus/gallery/migrations`

*  Run RBAC command:
 * ` php yii user/rbac/init`

* Also you need to configure filemanager module. By default it uses [responsivefilemanager](http://www.responsivefilemanager.com). Download it and place it somewhere in application Web root. For example you decided place it to '/plugins/responsivefilemanager', then configuration for module will be:
 * `'modules' => [
	'admin' => [
            'class' => 'rokorolov\parus\admin\Module',
            'fileManagerConfig' => [
                'responsiveFileManagerSrc' => '/plugins/responsivefilemanager/dialog.php?type=0'
            ]
	]
  ]`

## Admin login details

- Url: sites-public-url/admin/dashboard/dashboard/index (for this type of links you should enable pretty url)
- Admin user is 'admin' with password 'password'.

## Demo

- [Demo](http://avaliany.com/admin) (username: 'admin', password: 'password')

## Current project status

Parus is in alpha stage, so everything is not finished and can be changed at any time.
