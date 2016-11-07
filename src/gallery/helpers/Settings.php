<?php

namespace rokorolov\parus\gallery\helpers;

use rokorolov\parus\gallery\Module;
use Yii;

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
    
    public static function defaultLanguage()
    {
        return self::getConfig()['defaultLanguage'];
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
        return Yii::getAlias(self::getConfig()['album.introImageUploadPath']);
    }
    
    public static function albumIntroImageUploadSrc()
    {
        return Yii::getAlias(self::getConfig()['album.introImageUploadSrc']);
    }
    
    public static function albumIntroImageAllowedExtensions()
    {
        return self::getConfig()['album.introImageAllowedExtensions'];
    }
    
    public static function albumImageExtension()
    {
        return self::getConfig()['album.imageExtension'];
    }
    
    public static function albumImageTransformations()
    {
        return self::getConfig()['album.imageTransformations'];
    }
    
    public static function albumIntroImageDir()
    {
        return self::getConfig()['album.albumIntroImageDir'];
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
      
    public static function defaultLanguageTitle()
    {
        return self::languages()[self::defaultLanguage()];
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
