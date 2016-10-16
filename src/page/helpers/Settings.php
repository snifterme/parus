<?php

namespace rokorolov\parus\page\helpers;

use rokorolov\parus\page\Module;
use Yii;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings
{
    public static $config;
    
    const HOME_PAGE_YES = 1;
    const HOME_PAGE_NO = 0;

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
    
    public static function pageStatuses()
    {
        return self::getConfig()['pageStatuses'];
    }
    
    public static function pageDefaultStatus()
    {
        return self::getConfig()['pageDefaultStatus'];
    }
    
    public static function pageManagePageSize()
    {
        return self::getConfig()['pageManagePageSize'];
    }
    
    public static function imageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['imageUploadPath']);
    }
    
    public static function imageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['imageUploadSrc']);
    }
    
    public static function enableIntl()
    {
        return self::getConfig()['enableIntl'];
    }
    
    public static function homePageYesSign()
    {
        return self::HOME_PAGE_YES;
    }
    
    public static function homePageNoSign()
    {
        return self::HOME_PAGE_NO;
    }
    
    public static function pageDependencyTagName()
    {
        return \rokorolov\parus\page\models\Page::class;
    }
    
    public static function getConfig()
    {
        if (self::$config === null) {
            self::$config = Yii::$app->getModule(Module::MODULE_ID)->config;
        }
        return self::$config;
    }
}
