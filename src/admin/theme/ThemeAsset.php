<?php

namespace rokorolov\parus\admin\theme;

use yii\web\AssetBundle;

/**
 * This is the rokorolov\parus\themes\admin\ThemeAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ThemeAsset extends AssetBundle
{
    public $sourcePath = '@rokorolov/parus/admin/theme/assets';
    public $css = [
        'css/styles.css',
//        'css/less/styles.less',
    ];
    public $js = [
        'js/site.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'rokorolov\fontawesome\FontAwesomeAsset',
    ];
//    public $publishOptions = [
//        'forceCopy' => true,
//    ];
}