<?php

namespace rokorolov\parus\menu\services;

use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageMenu()
    {
        return Yii::$app->user->can('manageMenu');
    }
    
    public function canViewMenu()
    {
        return Yii::$app->user->can('viewMenu');  
    }
    
    public function canCreateMenu()
    {
        return Yii::$app->user->can('createMenu'); 
    }
    
    public function canUpdateMenu()
    {
        return Yii::$app->user->can('updateMenu');
    }
    
    public function canDeleteMenu($params)
    {
        if (!empty($params && (int)Settings::menuRootId() !== (int)$params->id)) {
            return Yii::$app->user->can('deleteMenu');
        }
        return false;
    }
    
    public function canManageMenuType()
    {
        return Yii::$app->user->can('manageMenuType');
    }
    
    public function canViewMenuType()
    {
        return Yii::$app->user->can('viewMenuType');  
    }
    
    public function canCreateMenuType()
    {
        return Yii::$app->user->can('createMenuType'); 
    }
    
    public function canUpdateMenuType()
    {
        return Yii::$app->user->can('updateMenuType');
    }
    
    public function canDeleteMenuType()
    {
        return Yii::$app->user->can('deleteMenuType');
    }
}
