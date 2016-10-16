<?php

namespace rokorolov\parus\admin\traits;

use Yii;

/**
 * Trait for translations
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait TranslationTrait 
{
    /**
     * Yii i18n messages configuration for generating translations
     *
     * @param string $path the directory path where translation files will exist
     * @param string $category the message category
     *
     * @return void
     */
    protected function initI18N($path, $category, $fileMap = null)
    {
        Yii::setAlias("@{$category}", $path);
        
        if (empty($this->i18n)) {
            $i18n = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => "@{$category}/messages",
                'forceTranslation' => true
            ];
            if ($fileMap != null) {
                $i18n['fileMap'] = [$category => $fileMap];
            }
        } else {
            $i18n = $this->i18n;
        }
        
        Yii::$app->i18n->translations["{$category}*"] = $i18n;
    }
}
