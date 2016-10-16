<?php

namespace rokorolov\parus\user\helpers;

use rokorolov\parus\user\Module;
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
    
    public static function userManagePageSize()
    {
        return self::getConfig()['user.managePageSize'];
    }
    
    public static function enableIntl()
    {
        return self::getConfig()['enableIntl'];
    }
    
    public static function userSystemId()
    {
        return self::getDefaultInstall()->getSystemId();
    }
    
    public static function userDependencyTagName()
    {
        return \rokorolov\parus\user\models\User::class;
    }
    
    public static function profileDependencyTagName()
    {
        return \rokorolov\parus\user\models\Profile::class;
    }
    
    public static function getDefaultInstall()
    {
        if (self::$defaultInstall === null) {
            self::$defaultInstall = Yii::createObject('rokorolov\parus\user\helpers\DefaultInstall');
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
