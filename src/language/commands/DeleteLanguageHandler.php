<?php

namespace rokorolov\parus\language\commands;

use rokorolov\parus\language\repositories\LanguageRepository;
use rokorolov\parus\language\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteLanguageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteLanguageHandler
{
    private $languageRepository;
    
    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }
    
    public function handle(DeleteLanguageCommand $command)
    {
        if (null === $language = $this->languageRepository->findById($command->getId())) {
            throw new LogicException('Language does not exist.');
        }
        
        $this->guardDefaultLanguage($language);
        
        $this->languageRepository->remove($language);
    }
    
    private function guardDefaultLanguage($language)
    {
        if ((int)$language->id === (int)Settings::defaultAppLanguage()) {
            throw new LogicException("You can't delete default language.");
        }
    }
}
