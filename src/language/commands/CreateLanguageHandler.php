<?php

namespace rokorolov\parus\language\commands;

use rokorolov\parus\language\repositories\LanguageRepository;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreateLanguageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateLanguageHandler
{
    use PurifierTrait;
    
    private $languageRepository;
    
    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }
    
    public function handle(CreateLanguageCommand $command)
    {
        $this->guardLangCodeIsUnique($command->getLangCode());
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $language = $this->languageRepository->makeLanguageCreateModel();
        $language->title = $this->textPurify($command->getTitle());
        $language->status = $command->getStatus();
        $language->order = $command->getOrder();
        $language->lang_code = $this->textPurify($command->getLangCode());
        $language->image =$this->textPurify( $command->getImage());
        $language->date_format = $this->textPurify($command->getDateFormat());
        $language->date_time_format = $this->textPurify($command->getDateTimeFormat());
        $language->created_at = $datetime;
        $language->updated_at = $datetime;
        
        $this->languageRepository->add($language);
        
        $command->model = $language;
    }
    
    private function guardLangCodeIsUnique($code)
    {
        if ($this->languageRepository->existsByLangCode($code)) {
            throw new LogicException("This language code $code is already exists.");
        }
    }
}