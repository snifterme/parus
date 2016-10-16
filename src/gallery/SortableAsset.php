<?php

namespace rokorolov\parus\gallery;

use yii\web\AssetBundle;

/**
 * This is the rokorolov\parus\gallery\SortableAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SortableAsset extends AssetBundle
{
    public $sourcePath = '@rokorolov/parus/gallery/assets';
    public $css = [

    ];
    public $js = [
        'js/Sortable.min.js'
    ];
    public $depends = [
        
    ];
}
