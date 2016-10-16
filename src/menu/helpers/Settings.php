<?php

namespace rokorolov\parus\menu\helpers;

use rokorolov\parus\menu\Module;
use Yii;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings
{
    const ORDER_FIRST = -1;
    const ORDER_LAST = -2;
    
    public static $config;
    
    public static $defaultInstall;

    public static function language()
    {
        return self::getConfig()['language'];
    }
    
    public static function languages()
    {
        return self::getConfig()['languages'];
    }
    
    public static function defaultLanguage()
    {
        return self::getConfig()['defaultLanguage'];
    }
    
    public static function menuStatuses()
    {
        return self::getConfig()['menu.statuses'];
    }
    
    public static function menuDefaultStatus()
    {
        return self::getConfig()['menu.defaultStatus'];
    }
    
    public static function linkPickers()
    {
        return self::getConfig()['linkPickers'];
    }
    
    public static function menuManagePageSize()
    {
        return self::getConfig()['menu.managePageSize'];
    }
    
    public static function menuRootId()
    {
        return self::getDefaultInstall()->getSystemRootId();
    }
    
    public static function menuTypeDependencyTagName()
    {
        return \rokorolov\parus\menu\models\MenuType::class;
    }
    
    public static function menuDependencyTagName()
    {
        return \rokorolov\parus\menu\models\Menu::class;
    }
    
    public static function orderFirst()
    {
        return self::ORDER_FIRST;
    }
    
    public static function orderLast()
    {
        return self::ORDER_LAST;
    }

    public static function getDefaultInstall()
    {
        if (self::$defaultInstall === null) {
            self::$defaultInstall = Yii::createObject('rokorolov\parus\menu\helpers\DefaultInstall');
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
