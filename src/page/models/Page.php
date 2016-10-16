<?php

namespace rokorolov\parus\page\models;

use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\traits\SoftDeleteTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\user\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $id
 * @property integer $status
 * @property string $hits
 * @property integer $created_by
 * @property string $created_at
 * @property integer $modified_by
 * @property string $modified_at
 */
class Page extends ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;

    use SoftDeleteTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::pageDependencyTagName();
    }
  
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifiedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'modified_by']);
    }
}
