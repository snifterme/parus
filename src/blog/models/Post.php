<?php

namespace rokorolov\parus\blog\models;

use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\traits\SoftDeleteTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\user\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $published_at
 * @property integer $status
 * @property string $hits
 * @property string $publish_up
 * @property string $publish_down
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property string $image
 *
 * @property User $updatedBy
 * @property Category $category
 * @property User $createdBy
 */
class Post extends ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;

    use SoftDeleteTrait;
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::postDependencyTagName();
    }
  
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
