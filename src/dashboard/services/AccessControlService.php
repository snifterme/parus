<?php

namespace rokorolov\parus\dashboard\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageDashboard()
    {
        return Yii::$app->user->can('manageDashboard');
    }
    
    public function canUpdatePost($params)
    {
        return Yii::createObject('rokorolov\parus\blog\services\AccessControlService')->canUpdatePost($params);
    }
}
