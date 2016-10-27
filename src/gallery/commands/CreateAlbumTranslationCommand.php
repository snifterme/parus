<?php

namespace rokorolov\parus\gallery\commands;

/**
 * CreateAlbumTranslationCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateAlbumTranslationCommand
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
