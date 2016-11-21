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
     * Get language.
     * 
     * @return array
     */
    public function getLanguage($key)
    {
        return ArrayHelper::getValue($this->getLanguages(), $key, null);
    }
    
    /**
     * Get language title.
     * 
     * @return string
     */
    public function getTitle($key)
    {
        if (null !== $language = $this->getLanguage($key)) {
            return $language['title'];
        }
        return null;
    }
    
    /**
     * Get language code.
     * 
     * @return string
     */
    public function getCode($key)
    {
         if (null !== $language = $this->getLanguage($key)) {
            return $language['lang_code'];
        }
        return null;
    }

    /**
     * Get language options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return ArrayHelper::map($this->getLanguages(), 'id', 'title');
    }
    
    /**
     * Get language array key codes.
     * 
     * @return array
     */
    public function getCodes()
    {
        return ArrayHelper::getColumn($this->getLanguages(), 'lang_code');
    }
    
    /**
     * Get language array key codes.
     * 
     * @return array
     */
    public function getKeyByCode($code)
    {
        $languages = ArrayHelper::index($this->getLanguages(), 'lang_code');
        
        return ArrayHelper::getValue($languages, $code . '.id', null);
    }
    
    /**
     * 
     * @param type $language
     */
    public function hasLanguage($key)
    {
        return array_key_exists($key, $this->getOptions());
    }
    
    /**
     * 
     * @param type $language
     */
    public function hasLanguageByCode($code)
    {
        return in_array($code, $this->getCodes());
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
                $languages = ArrayHelper::index($languages, 'id');
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
