<?php

namespace rokorolov\parus\settings\helpers;

use rokorolov\parus\settings\Module;
use Yii;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings
{
    public static $config;
    
    public static $defaultInstall;

    public static function language()
    {
        return self::getConfig()['language'];
    }
    
    public static function configuration()
    {
        return self::getConfig()['configuration'];
    }

    public static function settingsDependencyTagName()
    {
        return \rokorolov\parus\settings\models\Settings::class;
    }

    public static function getDefaultInstall()
    {
        if (self::$defaultInstall === null) {
            self::$defaultInstall = Yii::createObject('rokorolov\parus\settings\helpers\DefaultInstall');
        }
        return self::$defaultInstall;
    }
    
    public static function getConfig()
    {
        if (self::$config === null) {
            self::$config = Yii::$app->getModule(Module::MODULE_ID)->config;
        }
        return self::$config;
    }
}
