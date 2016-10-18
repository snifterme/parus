<?php

namespace rokorolov\parus\blog\helpers;

use rokorolov\parus\blog\Module;
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
    
    public static function languages()
    {
        return self::getConfig()['languages'];
    }
    
    public static function defaultLanguage()
    {
        return self::getConfig()['defaultLanguage'];
    }
                        
    public static function postIntroImageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['post.introImageUploadPath']);
    }
    
    public static function postIntroImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['post.introImageUploadSrc']);
    }
    
    public static function postIntroImageAllowedExtensions()
    {
        return self::getConfig()['post.introImageAllowedExtensions'];
    }
    
    public static function postImageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['post.imageUploadPath']);
    }
    
    public static function postImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['post.imageUploadSrc']);
    }
    
    public static function postImageExtension()
    {
        return self::getConfig()['post.imageExtension'];
    }
    
    public static function postImageTransformations()
    {
        return self::getConfig()['post.imageTransformations'];
    }
    
    public static function postStatuses()
    {
        return self::getConfig()['post.statuses'];
    }
    
    public static function postDefaultStatus()
    {
        return self::getConfig()['post.defaultStatus'];
    }
    
    public static function postManagePageSize()
    {
        return self::getConfig()['post.managePageSize'];
    }
    
    public static function categoryIntroImageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['category.introImageUploadPath']);
    }
    
    public static function categoryIntroImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['category.introImageUploadSrc']);
    }
    
    public static function categoryIntroImageAllowedExtensions()
    {
        return self::getConfig()['category.introImageAllowedExtensions'];
    }
    
    public static function categoryImageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['category.imageUploadPath']);
    }
    
    public static function categoryImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['category.imageUploadSrc']);
    }
    
    public static function categoryImageExtension()
    {
        return self::getConfig()['category.imageExtension'];
    }
    
    public static function categoryImageTransformations()
    {
        return self::getConfig()['category.imageTransformations'];
    }
    
    public static function categoryStatuses()
    {
        return self::getConfig()['category.statuses'];
    }
    
    public static function categoryDefaultStatus()
    {
        return self::getConfig()['category.defaultStatus'];
    }
    
    public static function categoryManagePageSize()
    {
        return self::getConfig()['category.managePageSize'];
    }
    
    public static function enableIntl()
    {
        return self::getConfig()['enableIntl'];
    }

    public static function categoryRootId()
    {
        return self::getDefaultInstall()->getSystemRootId();
    }
    
    public static function categoryDefaultId()
    {
        return self::getDefaultInstall()->getSystemDefaultId();
    }
    
    public static function postDependencyTagName()
    {
        return \rokorolov\parus\blog\models\Post::class;
    }
    
    public static function categoryDependencyTagName()
    {
        return \rokorolov\parus\blog\models\Category::class;
    }
    
    public static function getDefaultInstall()
    {
        if (self::$defaultInstall === null) {
            self::$defaultInstall = Yii::createObject('rokorolov\parus\blog\helpers\DefaultInstall');
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
