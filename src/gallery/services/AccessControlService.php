<?php

namespace rokorolov\parus\gallery\services;

use rokorolov\parus\admin\base\BaseAccessControl;
use Yii;

/**
 * AccessControlService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AccessControlService extends BaseAccessControl
{
    public function canManageAlbum()
    {
        return Yii::$app->user->can('manageAlbum');
    }
    
    public function canViewAlbum()
    {
        return Yii::$app->user->can('viewAlbum');  
    }
    
    public function canCreateAlbum()
    {
        return Yii::$app->user->can('createAlbum'); 
    }
    
    public function canUpdateAlbum()
    {
        return Yii::$app->user->can('updateAlbum');
    }
    
    public function canDeleteAlbum()
    {
        return Yii::$app->user->can('deleteAlbum');
    }
    
    public function canManagePhoto()
    {
        return Yii::$app->user->can('managePhoto');
    }
    
    public function canViewPhoto()
    {
        return Yii::$app->user->can('viewPhoto');  
    }
    
    public function canCreatePhoto()
    {
        return Yii::$app->user->can('createPhoto'); 
    }
    
    public function canUpdatePhoto()
    {
        return Yii::$app->user->can('updatePhoto');
    }
    
    public function canDeletePhoto()
    {
        return Yii::$app->user->can('deletePhoto');
    }
}
