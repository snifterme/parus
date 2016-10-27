<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
use rokorolov\parus\gallery\helpers\Settings;
use Yii;

/**
 * CreateAlbumTranslationHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateAlbumTranslationHandler
{
    private $albumRepository;
        
    public function __construct (
        AlbumRepository $albumRepository
    ) {
        $this->albumRepository = $albumRepository;
    }
    
    public function handle(CreateAlbumTranslationCommand $command)
    {
        $language = $command->getLanguageCode();
        $translations = $this->albumRepository->findTranslationsForNewLanguage(Settings::defaultLanguage());
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            foreach($translations as $translation) {
                $albumLanguage = $this->albumRepository->makeAlbumLangModel();
                $albumLanguage->album_id = $translation['album_id'];
                $albumLanguage->language = $language;
                $albumLanguage->name = $translation['name'];
                $albumLanguage->description = $translation['description'];
                
                $this->albumRepository->add($albumLanguage);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}