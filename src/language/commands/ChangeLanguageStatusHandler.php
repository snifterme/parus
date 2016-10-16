<?php

namespace rokorolov\parus\language\commands;

use rokorolov\parus\language\repositories\LanguageRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangeLanguageStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangeLanguageStatusHandler
{
    private $languageRepository;
    
    public function __construct(
        LanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
    }
    
    public function handle(ChangeLanguageStatusCommand $command)
    {
        if (null === $language = $this->languageRepository->findById($command->getId())) {
            throw new LogicException('Language does not exist.');
        }
        
        $language->status = $command->getStatus();
        $this->languageRepository->update($language);
    }

}
