<?php

namespace rokorolov\parus\admin\theme\widgets\translatable;

use yii\web\AssetBundle;

/**
 * This is the TranslatableAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class TranslatableAsset extends AssetBundle
{
    public $sourcePath = '@rokorolov/parus/admin/theme/widgets/translatable/assets';
    public $js = [
        'js/translatable.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
