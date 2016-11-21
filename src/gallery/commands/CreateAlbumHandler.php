<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use rokorolov\parus\admin\traits\PurifierTrait;
use Yii;

/**
 * CreateAlbumHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateAlbumHandler
{
    use PurifierTrait;
    
    private $albumRepository;
    
    public function __construct(
        AlbumRepository $albumRepository
    ) {
        $this->albumRepository = $albumRepository;
    }
    
    public function handle(CreateAlbumCommand $command)
    {
        $this->guardAlbumAliasIsUnique($command->getAlbumAlias());
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $album = $this->albumRepository->makeAlbumCreateModel();
        $album->status = $command->getStatus();
        $album->album_alias = $this->textPurify($command->getAlbumAlias());
        $album->created_at = $datetime;
        $album->updated_at = $datetime;
        
        $newImage = false;
        $imageFile = $command->getImageFile();
        if ($imageFile && !$imageFile->getHasError()) {
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                $imageFile->getBaseName(),
                $imageFile->tempName,
                null,
                Settings::albumIntroImageTransformations()
            ]);
            $album->image = $imageManager->getOriginalImageName();
            $newImage = true;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->albumRepository->add($album);
            
            foreach($command->getTranslations() as $translation) {
                $albumLanguage = $this->albumRepository->makeAlbumLangModel();
                $albumLanguage->album_id = $album->id;
                $albumLanguage->language = $translation['language'];
                $albumLanguage->name = $this->textPurify($translation['name']);
                $albumLanguage->description = $this->textPurify($translation['description']);

                $this->albumRepository->add($albumLanguage);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $album;
        
        if ($newImage) {
            $imageManager->setUploadPath(Settings::albumIntroImageUploadPath() . DIRECTORY_SEPARATOR . $album->id . DIRECTORY_SEPARATOR . Settings::albumIntroImageDir());
            $imageManager->save();
        }
    }

    private function guardAlbumAliasIsUnique($album_alias)
    {
        if ($this->albumRepository->existsByAlbumAlias($album_alias)) {
            throw new LogicException('Album alias already exists.');
        }
    }
}