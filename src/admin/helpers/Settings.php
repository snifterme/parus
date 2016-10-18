<?php

namespace rokorolov\parus\admin\helpers;

use rokorolov\parus\admin\Module;
use Yii;
use yii\helpers\Url;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings
{
    public static $config;
    
    public static function panelLanguage()
    {
        return isset(self::panelLanguages()[Yii::$app->language]) ? Yii::$app->language : self::panelDefaultLanguage();
    }
    
    public static function panelLanguages()
    {
        return self::getConfig()['panel.languages'];
    }
    
    public static function panelDefaultLanguage()
    {
        return self::getConfig()['panel.defaultLanguage'];
    }
    
    public static function frontendUrl()
    {
        return self::getConfig()['frontend.url'];
    }
    
    public static function panelUrl()
    {
        return self::getConfig()['panel.url'];
    }
    
    public static function profileUpdateUrl()
    {
        return Url::to(['/' . self::panelUrl() . '/user/user/update', 'id' => Yii::$app->user->id]);
    }
    
    public static function logoutUrl()
    {
        return Url::to(['/' . self::panelUrl() . '/user/authorization/logout']);
    }
    
    public static function clearCacheUrl()
    {
        return Url::to(['/' . self::panelUrl() . '/admin/clear-cache']);
    }
    
    public static function appName()
    {
        return self::getConfig()['app.name'];
    }
    
    public static function enableIntl()
    {
        return self::getConfig()['enableIntl'];
    }

    public static function getConfig()
    {
        if (self::$config === null) {
            self::$config = Yii::$app->getModule(Module::MODULE_ID)->config;
        }
        return self::$config;
    }
}
