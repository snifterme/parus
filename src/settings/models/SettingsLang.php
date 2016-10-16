<?php

namespace rokorolov\parus\settings\models;

use rokorolov\parus\language\models\Language;
use rokorolov\parus\settings\models\Settings;

/**
 * This is the model class for table "{{%settings_lang}}".
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 *
 * @property integer $settings_id
 * @property string $language
 * @property string $label
 *
 * @property Settings $settings
 */
class SettingsLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings_lang}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['lang_code' => 'language']);
    }
}
