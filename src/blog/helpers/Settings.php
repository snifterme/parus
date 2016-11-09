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
        return Yii::getAlias(self::postIntroImageConfig()['uploadPath']);
    }
    
    public static function postIntroImageUploadSrc()
    {
        return Yii::getAlias(self::postIntroImageConfig()['uploadSrc']);
    }
    
    public static function postIntroImageAllowedExtensions()
    {
        return self::postIntroImageConfig()['allowedExtensions'];
    }
    
    public static function postIntroImageAllowedMimeTypes()
    {
        return self::postIntroImageConfig()['allowedMimeTypes'];
    }
    
    public static function postIntroImageExtension()
    {
        return self::postIntroImageConfig()['extension'];
    }
    
    public static function postIntroImageMinSize()
    {
        return self::postIntroImageConfig()['minSize'];
    }
    
    public static function postIntroImageMaxSize()
    {
        return self::postIntroImageConfig()['maxSize'];
    }
    
    public static function postIntroImageMinWidth()
    {
        return self::postIntroImageConfig()['minWidth'];
    }
    
    public static function postIntroImageMaxWidth()
    {
        return self::postIntroImageConfig()['maxWidth'];
    }
    
    public static function postIntroImageMinHeight()
    {
        return self::postIntroImageConfig()['minHeight'];
    }
    
    public static function postIntroImageMaxHeight()
    {
        return self::postIntroImageConfig()['maxHeight'];
    }
    
    public static function postIntroImageTransformations()
    {
        return self::postIntroImageConfig()['transformations'];
    }
    
    public static function postIntroImageConfig()
    {
        return self::getConfig()['post.introImageConfig'];
    }
    
    public static function postImageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['post.imageUploadPath']);
    }
    
    public static function postImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['post.imageUploadSrc']);
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

    public static function categoryImageUploadPath()
    {
        return Yii::getAlias(self::getConfig()['category.imageUploadPath']);
    }
    
    public static function categoryImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['category.imageUploadSrc']);
    }
    public static function categoryIntroImageUploadPath()
    {
        return Yii::getAlias(self::categoryIntroImageConfig()['uploadPath']);
    }
    
    public static function categoryIntroImageUploadSrc()
    {
        return Yii::getAlias(self::categoryIntroImageConfig()['uploadSrc']);
    }
    
    public static function categoryIntroImageAllowedExtensions()
    {
        return self::categoryIntroImageConfig()['allowedExtensions'];
    }
    
    public static function categoryIntroImageAllowedMimeTypes()
    {
        return self::categoryIntroImageConfig()['allowedMimeTypes'];
    }
    
    public static function categoryIntroImageExtension()
    {
        return self::categoryIntroImageConfig()['extension'];
    }
    
    public static function categoryIntroImageMinSize()
    {
        return self::categoryIntroImageConfig()['minSize'];
    }
    
    public static function categoryIntroImageMaxSize()
    {
        return self::categoryIntroImageConfig()['maxSize'];
    }
    
    public static function categoryIntroImageMinWidth()
    {
        return self::categoryIntroImageConfig()['minWidth'];
    }
    
    public static function categoryIntroImageMaxWidth()
    {
        return self::categoryIntroImageConfig()['maxWidth'];
    }
    
    public static function categoryIntroImageMinHeight()
    {
        return self::categoryIntroImageConfig()['minHeight'];
    }
    
    public static function categoryIntroImageMaxHeight()
    {
        return self::categoryIntroImageConfig()['maxHeight'];
    }
    
    public static function categoryIntroImageTransformations()
    {
        return self::categoryIntroImageConfig()['transformations'];
    }
    
    public static function categoryIntroImageConfig()
    {
        return self::getConfig()['category.introImageConfig'];
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
