<?php

namespace rokorolov\parus\language\models;

use rokorolov\parus\language\helpers\Settings;
use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;

/**
 * This is the model class for table "{{%language}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $order
 * @property string $lang_code
 * @property string $image
 * @property string $date_format
 * @property string $date_time_format
 * @property integer $created_by
 * @property string $created_at
 * @property integer $modified_by
 * @property string $modified_at
 */
class Language extends \yii\db\ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    public function getDependencyTagId()
    {
        return Settings::languageDependencyTagName();
    }
}
