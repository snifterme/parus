<?php

namespace rokorolov\parus\gallery\helpers;

use rokorolov\parus\gallery\Module;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings
{
    public static $config;

    public static function language()
    {
        return self::getConfig()['language'];
    }
    
    public static function languages()
    {
        return self::getConfig()['languages'];
    }

    public static function panelLanguage()
    {
        return self::getConfig()['panelLanguage'];
    }
    
    public static function defaultLanguage()
    {
        return self::getConfig()['defaultLanguage'];
    }
    
    public static function languageOptions()
    {
        return ArrayHelper::map(self::languages(), 'id', 'title');
    }
      
    public static function defaultLanguageTitle()
    {
        return ArrayHelper::getValue(self::languages(), self::defaultLanguage() . '.title');
    }
    
    public static function translatableConfig()
    {
        return self::getConfig()['translatableConfig'];
    }
    
    public static function uploadImageConfig()
    {
        return self::getConfig()['uploadImageConfig'];
    }
  
    public static function uploadImageMapConfig()
    {
        return self::getConfig()['uploadImageMapConfig'];
    }
    
    public static function albumStatuses()
    {
        return self::getConfig()['album.statuses'];
    }
    
    public static function albumDefaultStatus()
    {
        return self::getConfig()['album.defaultStatus'];
    }
     
    public static function albumManagePageSize()
    {
        return self::getConfig()['album.managePageSize'];
    }
    
    public static function albumIntroImageUploadPath()
    {
        return Yii::getAlias(self::albumIntroImageConfig()['uploadPath']);
    }
    
    public static function albumIntroImageUploadSrc()
    {
        return Yii::getAlias(self::albumIntroImageConfig()['uploadSrc']);
    }
    
    public static function albumIntroImageAllowedExtensions()
    {
        return self::albumIntroImageConfig()['allowedExtensions'];
    }
    
    public static function albumIntroImageAllowedMimeTypes()
    {
        return self::albumIntroImageConfig()['allowedMimeTypes'];
    }
    
    public static function albumIntroImageExtension()
    {
        return self::albumIntroImageConfig()['extension'];
    }
    
    public static function albumIntroImageMinSize()
    {
        return self::albumIntroImageConfig()['minSize'];
    }
    
    public static function albumIntroImageMaxSize()
    {
        return self::albumIntroImageConfig()['maxSize'];
    }
    
    public static function albumIntroImageMinWidth()
    {
        return self::albumIntroImageConfig()['minWidth'];
    }
    
    public static function albumIntroImageMaxWidth()
    {
        return self::albumIntroImageConfig()['maxWidth'];
    }
    
    public static function albumIntroImageMinHeight()
    {
        return self::albumIntroImageConfig()['minHeight'];
    }
    
    public static function albumIntroImageMaxHeight()
    {
        return self::albumIntroImageConfig()['maxHeight'];
    }
    
    public static function albumIntroImageTransformations()
    {
        return self::albumIntroImageConfig()['transformations'];
    }
    
    public static function albumIntroImageDir()
    {
        return self::albumIntroImageConfig()['dir'];
    }
    
    public static function albumIntroImageConfig()
    {
        return self::getConfig()['album.introImageConfig'];
    }
    
    public static function photoStatuses()
    {
        return self::getConfig()['photo.statuses'];
    }
    
    public static function photoDefaultStatus()
    {
        return self::getConfig()['photo.defaultStatus'];
    }
    
    public static function photoManagePageSize()
    {
        return self::getConfig()['photo.managePageSize'];
    }
      
    public static function uploadFilePath($key = null)
    {
        return Yii::getAlias(self::uploadAlbumConfig($key)['uploadFilePath']);
    }
      
    public static function uploadFileSrc($key = null)
    {
        return Yii::getAlias(self::uploadAlbumConfig($key)['uploadFileSrc']);
    }
    
    public static function uploadAlbumConfig($key = null)
    {
        $albumConfig = self::uploadImageConfig();
        
        if (null === $key) {
            return $albumConfig;
        }
        
        $configMap = self::uploadImageMapConfig();
        
        if (isset($configMap[$key])) {
            $albumConfig = array_replace($albumConfig, $configMap[$key]);
        }
        
        return $albumConfig;
    }
    
    public static function enableIntl()
    {
        return self::getConfig()['enableIntl'];
    }
    
    public static function albumDependencyTagName()
    {
        return \rokorolov\parus\gallery\models\Album::class;
    }
    
    public static function photoDependencyTagName()
    {
        return \rokorolov\parus\gallery\models\Photo::class;
    }
    
    public static function getConfig()
    {
        if (self::$config === null) {
            self::$config = Yii::$app->getModule(Module::MODULE_ID)->config;
        }
        return self::$config;
    }
}
