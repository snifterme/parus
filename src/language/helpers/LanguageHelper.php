<?php

namespace rokorolov\parus\language\helpers;

use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use rokorolov\parus\language\helpers\Settings;
use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

/**
 * This is the LanguageHelper.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguageHelper
{
    /**
     *
     * @var type 
     */
    public $language;

    /**
     * @var array 
     */
    private static $languages;
    
    /**
     * Get default language.
     * 
     * @return array
     */
    public function getDefault($langCode)
    {
        return $this->getLanguages()[$langCode];
    }
    
    /**
     * Get default language title.
     * 
     * @return string
     */
    public function getDefaultTitle($langCode)
    {
        return $this->getLanguages()[$langCode]['title'];
    }
    
    /**
     * Get default language code.
     * 
     * @return string
     */
    public function getDefaultCode($langCode)
    {
        return $this->getLanguages()[$langCode]['lang_code'];
    }
    
    /**
     * Get language titles.
     * 
     * @return array
     */
    public function getTitles()
    {
        return ArrayHelper::map($this->getLanguages(), 'lang_code', 'title');
    }

    /**
     * Get language options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return ArrayHelper::map($this->getLanguages(), 'lang_code', 'title');
    }
    
    /**
     * Get language array key codes.
     * 
     * @return array
     */
    public function getKeyCodes()
    {
        return array_keys($this->getOptions());
    }
    
    /**
     * 
     * @param type $language
     */
    public function hasLanguage($language)
    {
        return array_key_exists($language, $this->getOptions());
    }

    /**
     * Get languages.
     * 
     * @return array
     */
    protected function getLanguages()
    {
        if (null === self::$languages) {
            $cacheKey = static::class;
            if (false === $languages = Yii::$app->cache->get($cacheKey)) {
                $languageRepository = Yii::createObject('rokorolov\parus\language\repositories\LanguageReadRepository');
                if (null === $languages = $languageRepository->getLanguagesAsArray()) {
                    throw new NotFoundHttpException;
                }
                Yii::$app->cache->set(
                    $cacheKey,
                    $languages,
                    86400,
                    new TagDependency(
                        [
                            'tags' => [
                                TagDependencyNamingHelper::getCommonTag(Settings::languageDependencyTagName()),
                            ]
                        ]
                    )
                );
            }
            self::$languages = $languages;
        }
        return self::$languages;
    }
}
