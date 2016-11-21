<?php

namespace rokorolov\parus\dashboard\helpers;

use rokorolov\parus\dashboard\Module;
use Yii;

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
        return self::getConfig()['panelLanguage'];
    }

    public static function popularPostLimit()
    {
        return self::getConfig()['post.popularLimit'];
    }

    public static function lastAddedPostLimit()
    {
        return self::getConfig()['post.lastAddedLimit'];
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
