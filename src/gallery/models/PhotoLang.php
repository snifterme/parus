<?php

namespace rokorolov\parus\gallery\models;

use rokorolov\parus\language\models\Language;

/**
 * This is the model class for table "{{%photo_lang}}".
 *
 * @property integer $photo_id
 * @property string $caption
 * @property string $language
 *
 * @property Language $language0
 * @property Photo $photo
 */
class PhotoLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photo_lang}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['lang_code' => 'language']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photo::className(), ['id' => 'photo_id']);
    }
}
