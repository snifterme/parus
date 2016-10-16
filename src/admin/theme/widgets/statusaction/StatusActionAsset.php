<?php

namespace rokorolov\parus\admin\theme\widgets\statusaction;

/**
 * StatusActionAsset is a bundle.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class StatusActionAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@rokorolov/parus/admin/theme/widgets/statusaction/assets';
    public $js = [
        'js/statusaction.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
