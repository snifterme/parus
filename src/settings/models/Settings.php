<?php

namespace rokorolov\parus\settings\models;

use rokorolov\parus\settings\helpers\Settings as SettingsHelper;
use rokorolov\parus\settings\models\SettingsLang;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\admin\traits\TagDependencyTrait;

/**
 * This is the model class for table "{{%settings}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $id
 * @property string $param
 * @property string $value
 * @property string $default
 * @property string $type
 * @property string $order
 * @property string $created_at
 * @property string $modified_at
 *
 * @property SettingsLang[] $settingsLangs
 */
class Settings extends \yii\db\ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return SettingsHelper::settingsDependencyTagName();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(SettingsLang::className(), ['settings_id' => 'id']);
    }

}
