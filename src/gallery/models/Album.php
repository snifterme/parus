<?php

namespace rokorolov\parus\gallery\models;

use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\admin\traits\TagDependencyTrait;

/**
 * This is the model class for table "{{%album}}".
 *
 * @property integer $id
 * @property string $status
 * @property string $created_at
 * @property string $modified_at
 *
 * @property AlbumLang[] $AlbumLangs
 * @property Language[] $languages
 * @property Photo[] $photos
 */
class Album extends \yii\db\ActiveRecord implements HasTagDependency
{
    use TagDependencyTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%album}}';
    }
    
    /**
     * 
     * @return string
     */
    public function getDependencyTagId()
    {
        return Settings::albumDependencyTagName();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(AlbumLang::className(), ['album_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['album_id' => 'id']);
    }
}
