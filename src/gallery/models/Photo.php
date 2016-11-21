<?php

namespace rokorolov\parus\gallery\models;

use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\admin\traits\TagDependencyTrait;
use rokorolov\parus\language\models\Language;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "{{%photo}}".
 *
 * @property integer $id
 * @property string $status
 * @property integer $album_id
 * @property string $photo_name
 * @property string $photo_size
 * @property string $photo_extension
 * @property string $photo_mime
 * @property string $photo_path
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Album $album
 * @property PhotoLang[] $photoLangs
 * @property Language[] $languages
 */
class Photo extends \yii\db\ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photo}}';
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::photoDependencyTagName();
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'order',
                'groupAttributes' => [
                    'album_id'
                ],
            ],
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(Album::className(), ['id' => 'album_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PhotoLang::className(), ['photo_id' => 'id']);
    }
}
