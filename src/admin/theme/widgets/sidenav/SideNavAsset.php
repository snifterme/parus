<?php

namespace rokorolov\parus\admin\theme\widgets\sidenav;

use yii\web\AssetBundle;

/**
 * This is the SideNavAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SideNavAsset extends AssetBundle
{
    public $sourcePath = '@rokorolov/parus/admin/theme/widgets/sidenav/assets';
    public $css = [
        'css/sidenav.css'
    ];
    public $js = [
        'js/sidenav.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
