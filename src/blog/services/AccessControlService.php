<?php

namespace rokorolov\parus\blog\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use rokorolov\parus\blog\helpers\Settings;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManagePost()
    {
        return Yii::$app->user->can('managePost');
    }
    
    public function canViewPost()
    {
        return Yii::$app->user->can('viewPost');  
    }
    
    public function canCreatePost()
    {
        return Yii::$app->user->can('createPost'); 
    }
    
    public function canUpdatePost($params)
    {
        if (!empty($params)) {
            return Yii::$app->user->can('updatePost', $params->created_by);
        }
        return false;
    }
    
    public function canDeletePost($params)
    {
        if (!empty($params)) {
            return Yii::$app->user->can('deletePost', $params->created_by);
        }
        return false;
    }
    
    public function canManageCategory()
    {
        return Yii::$app->user->can('manageCategory');
    }
    
    public function canViewCategory()
    {
        return Yii::$app->user->can('viewCategory');  
    }
    
    public function canCreateCategory()
    {
        return Yii::$app->user->can('createCategory'); 
    }
    
    public function canUpdateCategory($params)
    {
        if (!empty($params)) {
            return Yii::$app->user->can('updateCategory', $params->created_by);
        }
        return false;
    }
    
    public function canDeleteCategory($params)
    {
        if (!empty($params && ((int)Settings::categoryDefaultId() !== (int) $params->id && (int)Settings::categoryRootId() !== (int) $params->id))) {
            return Yii::$app->user->can('deleteCategory', $params->created_by);
        }
        return false;
    }
}
