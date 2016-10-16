<?php

namespace rokorolov\parus\admin\theme;

use yii\web\AssetBundle;

/**
 * This is the rokorolov\parus\admin\theme\MagnificPopupAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MagnificPopupAsset extends AssetBundle
{
    public $sourcePath = '@bower/magnific-popup/dist';
    public $css = [
        'magnific-popup.css',
    ];
    public $js = [
        'jquery.magnific-popup.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
