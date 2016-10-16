<?php

namespace rokorolov\parus\admin\theme\helpers;

use rokorolov\parus\admin\helpers\Settings;
use Yii;

/**
 * ThemeHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ThemeHelper
{
    public function topNavCurrentLanguage()
    {
        return $this->topNavLanguageItems()[Settings::panelLanguage()];
    }
    
    public function appName()
    {
        return Settings::appName();
    }
    
    public function frontendUrl()
    {
        return Settings::frontendUrl();
    }
    
    public function profileUpdateUrl()
    {
        return Settings::profileUpdateUrl();
    }
    
    public function logoutUrl()
    {
        return Settings::logoutUrl();
    }
    
    public function clearCacheUrl()
    {
        return Settings::clearCacheUrl();
    }
    
    public function topNavLanguages()
    {
        $items = $this->topNavLanguageItems();
        unset($items[Settings::panelLanguage()]);
        return $items;
    }

    public function topNavLanguageItems()
    {
        $items = [];
        $assetUrl = $this->assetBaseUrl();
        foreach($this->adminLanguages() as $key => $value) {
            $items[$key] = [
                'label' => "<img src='$assetUrl/images/flag-$key.png' class='top-nav-item-lang-flag'></img>" . (strcmp($key, Settings::panelLanguage()) === 0 ? '' : $value),
                'url' => '#',
                'linkOptions' => ['class' => 'top-nav-item-sublink js-change-lang', 'data-lang' => $key],
            ];
        }
        return $items;
    }
    
    public function adminLanguages()
    {
        return Settings::panelLanguages();
    }
    
    public function topNav()
    {
        return Yii::$app->view->render('partials/_top-nav', [
            'theme' => $this
        ]);
    }
    
    public function sideNavItems()
    {
        return [
            ['label' => self::t('theme', 'Dashboard'), 'url' => ['/admin/dashboard/dashboard/index'], 'icon' => 'tachometer'],
            ['label' => self::t('theme', 'Content'), 'header' => true],
            ['label' => self::t('theme', 'Posts'), 'url' => ['/admin/blog/post/index'], 'icon' => 'pencil-square-o', 'items' => [
                ['label' => self::t('theme', 'Manage Posts'), 'url' => ['/admin/blog/post/index']],
                ['label' => self::t('theme', 'Add Post'), 'url' => ['/admin/blog/post/create']],
            ], 'active' => strpos(Yii::$app->view->context->route, 'admin/blog/post') === 0],
            ['label' => self::t('theme', 'Categories'), 'url' => ['/admin/blog/category/index'], 'icon' => 'folder-open', 'items' => [
                ['label' => self::t('theme', 'Manage Categories'), 'url' => ['/admin/blog/category/index']],
                ['label' => self::t('theme', 'Add Category'), 'url' => ['/admin/blog/category/create']],
            ], 'active' => strpos(Yii::$app->view->context->route, 'admin/blog/category') === 0],
            ['label' => self::t('theme', 'Pages'), 'url' => ['/admin/page/page/index'], 'icon' => 'tablet', 'items' => [
                ['label' => self::t('theme', 'Manage Pages'), 'url' => ['/admin/page/page/index']],
                ['label' => self::t('theme', 'Add Page'), 'url' => ['/admin/page/page/create']],
            ], 'active' => strpos(Yii::$app->view->context->route, 'admin/page/page') === 0],
            ['label' => self::t('theme', 'Menu'), 'url' => ['/admin/menu/menu/index'], 'icon' => 'bars',
                'active' => strpos(Yii::$app->view->context->route, 'admin/menu/menu') === 0],
            ['label' => self::t('theme', 'Widgets'), 'header' => true],
            ['label' => self::t('theme', 'Gallery'), 'url' => ['/admin/gallery/album/index'], 'icon' => 'camera',
                'active' => strpos(Yii::$app->view->context->route, 'admin/gallery/album') === 0 || strpos(Yii::$app->view->context->route, 'admin/gallery/photo') === 0],
            ['label' => self::t('theme', 'Setup'), 'header' => true],
            ['label' => self::t('theme', 'Languages'), 'url' => ['/admin/language/language/index'], 'icon' => 'language',
                'active' => strpos(Yii::$app->view->context->route, 'admin/language/language') === 0],
            ['label' => self::t('theme', 'Settings'), 'url' => ['/admin/settings/settings/index'], 'icon' => 'wrench'],
            ['label' => self::t('theme', 'Access'), 'header' => true],
            ['label' => self::t('theme', 'Users'), 'url' => ['/admin/user/user/index'], 'icon' => 'user', 'items' => [
                ['label' => self::t('theme', 'Manage Users'), 'url' => ['/admin/user/user/index']],
                ['label' => self::t('theme', 'Add User'), 'url' => ['/admin/user/user/create']],
            ], 'active' => strpos(Yii::$app->view->context->route, 'admin/user/user') === 0],
            ['label' => self::t('theme', 'Managers'), 'header' => true],
            ['label' => self::t('theme', 'File Manager'), 'url' => ['/admin/filemanager/filemanager/index'], 'icon' => 'cloud-upload']
        ];
    }
    
    public function assetBaseUrl()
    {
        return Yii::$app->assetManager->getBundle('rokorolov\parus\admin\theme\ThemeAsset')->baseUrl;
    }

    public function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/theme'])) {
            Yii::$app->i18n->translations['rokorolov/parus/theme'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/admin/theme/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'rokorolov/parus/theme' => 'theme.php',
                ]
            ];
        }
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('rokorolov/parus/' . $category, $message, $params, $language);
    }
}