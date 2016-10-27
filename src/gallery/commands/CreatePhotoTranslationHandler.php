<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\PhotoRepository;
use rokorolov\parus\gallery\helpers\Settings;
use Yii;

/**
 * CreatePhotoTranslationHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePhotoTranslationHandler
{
    private $photoRepository;
        
    public function __construct (
        PhotoRepository $photoRepository
    ) {
        $this->photoRepository = $photoRepository;
    }
    
    public function handle(CreatePhotoTranslationCommand $command)
    {
        $language = $command->getLanguageCode();
        $translations = $this->photoRepository->findTranslationsForNewLanguage(Settings::defaultLanguage());
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            foreach($translations as $translation) {
                $photoLanguage = $this->photoRepository->makePhotoLangModel();
                $photoLanguage->photo_id = $translation['photo_id'];
                $photoLanguage->language = $language;
                $photoLanguage->caption = $translation['caption'];
                $photoLanguage->description = $translation['description'];
                
                $this->photoRepository->add($photoLanguage);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}