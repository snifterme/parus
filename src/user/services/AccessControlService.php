<?php

namespace rokorolov\parus\user\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageUser()
    {
        return Yii::$app->user->can('manageUser');
    }
    
    public function canViewUser()
    {
        return Yii::$app->user->can('viewUser');  
    }
    
    public function canCreateUser()
    {
        return Yii::$app->user->can('createUser'); 
    }
    
    public function canUpdateUser($params)
    {
        if (isset($params['author_id']) && !empty($params['author_id'])) {
            return Yii::$app->user->can('updateUser', $params);
        }
        return false;
    }
    
    public function canDeleteUser($params)
    {
        if (isset($params['author_id']) && !empty($params['author_id'])) {
            return Yii::$app->user->can('deleteOwnUser', $params);
        }
        return false;
    }
}
