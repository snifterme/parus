<?php

namespace rokorolov\parus\user\models;

use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\traits\SoftDeleteTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\user\helpers\Settings;
use rokorolov\parus\user\helpers\SecurityHelper;
use Yii;
use yii\db\ActiveRecord;

/**
* This is the model class for table "{{%user}}".
* 
* @author Roman Korolov <rokorolov@gmail.com>
*
* @property integer $id
* @property string $username
* @property string $auth_key
* @property string $password_hash
* @property string $password_reset_token
* @property string $email
* @property integer $status
* @property integer $created_at
* @property integer $updated_at
*
* @property Category[] $categories
* @property Language[] $languages
* @property Post[] $posts
*/
class User extends ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;

    use SoftDeleteTrait;
    
    private $security;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getPosts() 
    { 
        return $this->hasMany(Post::className(), ['updated_by' => 'id']); 
    }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->getSecurityHelper()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = $this->getSecurityHelper()->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function setAuthKey()
    {
        $this->auth_key = $this->getSecurityHelper()->generateAuthKey();
    }

    /**
     * Generates new password reset token
     */
    public function setPasswordResetToken()
    {
        $this->password_reset_token = $this->getSecurityHelper()->generatePasswordResetToken();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::userDependencyTagName();
    }
    
    /**
     * 
     * @return type
     */
    protected function getSecurityHelper()
    {
        if ($this->security === null) {
            $this->security = Yii::createObject(SecurityHelper::class);
        }
        return $this->security;
    }
    
}
