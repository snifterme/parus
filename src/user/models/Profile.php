<?php

namespace rokorolov\parus\user\models;

use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\user\helpers\Settings;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%profile}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property string $avatar_url
 * @property string $last_login_on
 * @property string $last_login_ip
 *
 * @property User $user
 */
class Profile extends ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::profileDependencyTagName();
    }
}
