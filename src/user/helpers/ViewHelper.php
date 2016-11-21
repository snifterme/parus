<?php

namespace rokorolov\parus\user\helpers;

use rokorolov\parus\user\Module;
use Yii;
use yii\helpers\Inflector;

/**
 * ViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ViewHelper
{
    private $languageHelper;
    private $rbacService;
    
    /**
     * 
     * @return type
     */
    public function getLanguageOptions()
    {
        return $this->getLanguageHelper()->getOptions();
    }
    
    /**
     * 
     * @return type
     */
    public function getRoleOptions()
    {
        return $this->getRbacService()->getOptions();
    }
    
    /**
     * 
     * @return type
     */
    public function getAllRoles()
    {
        return $this->getRbacService()->getRoles();
    }
    
    /**
     * 
     * @return array
     */
    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('user', 'Id'),
            'username' => Module::t('user', 'Username'),
            'email' => Module::t('user', 'Email'),
            'role' => Module::t('user', 'Role'),
            'status' => Module::t('user', 'Status'),
            'name' => Module::t('user', 'Name'),
            'surname' => Module::t('user', 'Surname'),
            'last_login_on' => Module::t('user', 'Last Visit'),
            'last_login_ip' => Module::t('user', 'Last login ip'),
            'language' => Module::t('user', 'Language'),
            'avatar_url' => Module::t('user', 'Avatar link'),
            'current_password' => Module::t('user', 'Current password'),
            'new_password' => Module::t('user', 'New Password'),
            'password' => Module::t('user', 'Password'),
            'repeat_password' => Module::t('user', 'Repeat password'),
            'created_at' => Module::t('user', 'Created on'),
            'updated_at' => Module::t('user', 'Last edited on'),
            'deleted_at' => Module::t('user', 'Deleted'),
            'auth_key' => Module::t('user', 'Auth key'),
            'password_hash' => Module::t('user', 'Password hash'),
            'password_reset_token' => Module::t('user', 'Password reset token'),
        ];
    }
    
    /**
     * 
     * @param string $attributeName
     * @return string
     */
    public function getAttributeLabel($attributeName)
    {
        return isset($this->getAttributeLabels()[$attributeName]) ? $this->getAttributeLabels()[$attributeName] : Inflector::camel2words($attributeName, true);
    }
    
    /**
     * 
     * @return object
     */
    protected function getLanguageHelper()
    {
        if ($this->languageHelper === null) {
            $this->languageHelper = Yii::createObject('rokorolov\parus\language\helpers\LanguageHelper');
        }
        return $this->languageHelper;
    }
    /**
     * 
     * @return object
     */
    protected function getRbacService()
    {
        if ($this->rbacService === null) {
            $this->rbacService = Yii::createObject('rokorolov\parus\admin\contracts\RbacServiceInterface');
        }
        return $this->rbacService;
    }
}
