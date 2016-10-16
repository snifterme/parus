<?php

namespace rokorolov\parus\menu\models;

use rokorolov\parus\menu\models\MenuType;
use rokorolov\parus\menu\models\query\MenuQuery;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\admin\contracts\HasTagDependency;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%menu}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $id
 * @property integer $menu_type_id
 * @property string $link
 * @property string $note
 * @property string $parent_id
 * @property integer $status
 * @property integer $depth
 * @property integer $lft
 * @property integer $rgt
 *
 */
class Menu extends ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'nestedSets' => [
                'class' => NestedSetsBehavior::className(),
            ]
        ];
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::menuDependencyTagName();
    }
    
    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuType()
    {
        return $this->hasOne(MenuType::className(), ['id' => 'menu_type_id']);
    }
        
    /**
     * @return array
     */
    public static function getMenuTypes()
    {
        return MenuType::find()->select('id, title')->asArray()->all();
    }
}
