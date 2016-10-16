<?php

namespace rokorolov\parus\admin\theme\widgets\sidenav;

use yii\web\AssetBundle;

/**
 * This is the SlimScrollAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SlimScrollAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-slimscroll';
    public $js = [
        'jquery.slimscroll.min.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
