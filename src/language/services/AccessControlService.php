<?php

namespace rokorolov\parus\language\services;

use rokorolov\parus\language\helpers\Settings;
use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageLanguage()
    {
        return Yii::$app->user->can('manageLanguage');
    }
    
    public function canViewLanguage()
    {
        return Yii::$app->user->can('viewLanguage');  
    }
    
    public function canCreateLanguage()
    {
        return Yii::$app->user->can('createLanguage'); 
    }
    
    public function canUpdateLanguage()
    {
        return Yii::$app->user->can('updateLanguage');
    }
    
    public function canDeleteLanguage($params)
    {
        if (!empty($params) && (strcmp($params->lang_code, Settings::defaultAppLanguage()) !== 0)) {
            return Yii::$app->user->can('deleteLanguage');
        }
        return false;
    }
}
