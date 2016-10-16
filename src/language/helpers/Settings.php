<?php

namespace rokorolov\parus\language\helpers;

use rokorolov\parus\language\Module;
use Yii;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings
{
    public static $config;
    
    public static function defaultAppLanguage()
    {
        return self::getConfig()['defaultAppLanguage'];
    }
    
    public static function languageStatuses()
    {
        return self::getConfig()['language.statuses'];
    }
    
    public static function languageDefaultStatus()
    {
        return self::getConfig()['language.defaultStatus'];
    }
    
    public static function languageManagePageSize()
    {
        return self::getConfig()['language.managePageSize'];
    }
    
    public static function enableIntl()
    {
        return self::getConfig()['enableIntl'];
    }
    
    public static function languageDependencyTagName()
    {
        return \rokorolov\parus\language\models\Language::class;
    }
    
    public static function getConfig()
    {
        if (self::$config === null) {
            self::$config = Yii::$app->getModule(Module::MODULE_ID)->config;
        }
        return self::$config;
    }
}
