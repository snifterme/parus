<?php

namespace rokorolov\parus\gallery;

use yii\web\AssetBundle;

/**
 * This is the rokorolov\parus\gallery\GalleryAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@rokorolov/parus/gallery/assets';
    public $css = [
        'css/gallery.css',
    ];
    public $js = [
        'js/Sortable.min.js'
    ];
    public $depends = [
        'rokorolov\parus\admin\theme\ThemeAsset'
    ];
}
