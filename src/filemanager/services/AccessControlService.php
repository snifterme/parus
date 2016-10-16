<?php

namespace rokorolov\parus\filemanager\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageFilemanager()
    {
        return Yii::$app->user->can('manageFilemanager');
    }
}
