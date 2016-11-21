<?php

namespace rokorolov\parus\gallery\models;

/**
 * This is the model class for table "{{%album_lang}}".
 *
 * @property integer $album_id
 * @property string $name
 * @property string $description
 * @property string $language
 *
 * @property Album $album
 * @property Language $language0
 */
class AlbumLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%album_lang}}';
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
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language']);
    }
}
