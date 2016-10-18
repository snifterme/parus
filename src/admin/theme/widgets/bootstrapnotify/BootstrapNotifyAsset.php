<?php

namespace rokorolov\parus\admin\theme\widgets\bootstrapnotify;

use yii\web\AssetBundle;

/**
 * This is the rokorolov\parus\admin\theme\BootstrapNotifyAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class BootstrapNotifyAsset extends AssetBundle
{
    public $sourcePath = '@bower/remarkable-bootstrap-notify';
    public $js = [
        'bootstrap-notify.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
