<?php

namespace rokorolov\parus\gallery\commands;

/**
 * CreatePhotoTranslationCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePhotoTranslationCommand
{
    private $language_code;
    
    public function __construct($language_code)
    {
        $this->language_code = $language_code;
    }
    
    public function getLanguageCode()
    {
        return $this->language_code;
    }
}
