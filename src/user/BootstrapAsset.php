<?php

namespace rokorolov\parus\user;

use yii\web\AssetBundle;

/**
 * This is the rokorolov\parus\user\BootstrapAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $css = [
        'css/bootstrap.css',
    ];
}
