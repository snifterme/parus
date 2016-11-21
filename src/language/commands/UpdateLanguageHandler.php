<?php

namespace rokorolov\parus\language\commands;

use rokorolov\parus\language\repositories\LanguageRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use rokorolov\parus\admin\traits\PurifierTrait;
use Yii;

/**
 * UpdateLanguageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateLanguageHandler
{
    use PurifierTrait;
    
    private $languageRepository;
    
    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }
    
    public function handle(UpdateLanguageCommand $command)
    {
        $this->guardLangCodeIsUnique($command->getLangCode(), $command->getId());
        
        if (null === $language = $this->languageRepository->findById($command->getId())) {
            throw new LogicException('Language does not exist.');
        }
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $language->title = $this->textPurify($command->getTitle());
        $language->status = $command->getStatus();
        $language->order = $command->getOrder();
        $language->lang_code = $this->textPurify($command->getLangCode());
        $language->image =$this->textPurify( $command->getImage());
        $language->date_format = $this->textPurify($command->getDateFormat());
        $language->date_time_format = $this->textPurify($command->getDateTimeFormat());
        $language->updated_at = $datetime;
        
        $this->languageRepository->update($language);
        
        $command->model = $language;
    }
    
    
    private function guardLangCodeIsUnique($code, $id)
    {
        if ($this->languageRepository->existsByLangCode($code, $id)) {
            throw new LogicException("This language code $code is already exists.");
        }
    }
}