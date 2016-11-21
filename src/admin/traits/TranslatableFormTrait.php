<?php

namespace rokorolov\parus\admin\traits;

/**
 * TranslatableFormTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait TranslatableFormTrait
{
    protected $translatableConfig;
    protected $translationVariations;
    
    public function translate($language = null)
    {
        $language === null && $language = $this->translatableConfig['language'];
        
        return $this->getTranslation($language);
    }
    
    public function getTranslationVariations()
    {
        if ($this->translationVariations === null) {
            $translations = [];
            foreach($this->translatableConfig['languages'] as $key => $language) {
                $translations[$key] = $this->getTranslation($key);
            }
            $this->translationVariations = $translations;
        }
        return $this->translationVariations;
    }
    
    protected function getTranslation($language)
    {
        $translationAtrribute = $this->translatableConfig['translationLanguageAttribute'];
        foreach($this->translations as $translation) {
            if ((string)$translation->{$translationAtrribute} === (string)$language) {
                return $translation;
            }
        }
        
        $translation = $this->getTranslatableModel();
        $translation->{$translationAtrribute} = $language;
        
        return $translation;
    }
    
    protected function reverseTranslations()
    {
        $defaultLanguageCode = $this->translatableConfig['defaultLanguage'];
        $translationVariations = $this->translationVariations;
        $defaultTranslation = $translationVariations[$defaultLanguageCode];
        
        foreach ($translationVariations as $language => $translations) {
            $translations = $translations->getAttributes();
            if ($this->translatableConfig['automaticEmptyFiledsTranslation'] && $defaultLanguageCode !== $language) {
                foreach ($translations as $attribute => $translation) {
                    $translations[$attribute] = !empty($translation) || in_array($attribute, $this->translatableConfig['automaticEmptyFiledsTranslationException']) ? $translation : $defaultTranslation[$attribute];
                }
            }
            $data[$language] = $translations;
        }
        
        return $data;
    }
}
