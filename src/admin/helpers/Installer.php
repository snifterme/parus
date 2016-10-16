<?php

namespace rokorolov\parus\admin\helpers;

use Yii;
use yii\helpers\FileHelper;

/**
 * Installer
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Installer
{
    public static function init()
    {
        FileHelper::createDirectory(Yii::getAlias('@app/web/uploads/media'), 0775, true);
    }
}
