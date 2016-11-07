<?php

namespace rokorolov\parus\menu\models;

use rokorolov\parus\menu\models\Menu;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%menu_type}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $id
 * @property string $menu_type_alias
 * @property string $title
 * @property string $description
 */
class MenuType extends ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_type}}';
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::menuTypeDependencyTagName();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(Menu::className(), ['menu_type_id' => 'id']);
    }
}
