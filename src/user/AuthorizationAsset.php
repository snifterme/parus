<?php

namespace rokorolov\parus\user;

/**
 * This is the AuthorizationAsset.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AuthorizationAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@rokorolov/parus/user/assets';
    public $css = ['css/authorization.css'];
    public $depends = [
        'yii\web\YiiAsset',
        'rokorolov\parus\user\BootstrapAsset',
        'rokorolov\fontawesome\FontAwesomeAsset',
    ];
}
