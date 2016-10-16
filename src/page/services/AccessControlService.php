<?php

namespace rokorolov\parus\page\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManagePage()
    {
        return Yii::$app->user->can('managePage');
    }
    
    public function canViewPage()
    {
        return Yii::$app->user->can('viewPage');  
    }
    
    public function canCreatePage()
    {
        return Yii::$app->user->can('createPage'); 
    }
    
    public function canUpdatePage($params)
    {
        if (!empty($params)) {
            return Yii::$app->user->can('updatePage', $params->created_by);
        }
        return false;
    }
    
    public function canDeletePage($params)
    {
        if (!empty($params)) {
            return Yii::$app->user->can('deletePage', $params->created_by);
        }
        return false;
    }
}
