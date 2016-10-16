<?php

namespace rokorolov\parus\settings\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageSettings()
    {
        return Yii::$app->user->can('manageSettings');
    }
    
    public function canViewSettings()
    {
        return Yii::$app->user->can('viewSettings');  
    }
    
    public function canCreateSettings()
    {
        return Yii::$app->user->can('createSettings'); 
    }
    
    public function canUpdateSettings()
    {
        return Yii::$app->user->can('updateSettings');
    }
    
    public function canDeleteSettings()
    {
        return Yii::$app->user->can('deleteSettings');
    }
}
