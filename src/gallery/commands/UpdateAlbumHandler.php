<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * UpdateAlbumHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateAlbumHandler
{
    use PurifierTrait;
    
    private $albumRepository;
    
    public function __construct(
        AlbumRepository $albumRepository
    ) {
        $this->albumRepository = $albumRepository;
    }
    
    public function handle(UpdateAlbumCommand $command)
    {
        $this->guardAlbumAliasIsUnique($command->getAlbumAlias(), $command->getId());
        
        if (null === $album = $this->albumRepository->findById($command->getId())) {
            throw new LogicException('Post does not exist.');
        }
        
        $album->status = $command->getStatus();
        $album->album_alias = $this->textPurify($command->getAlbumAlias());
        $album->updated_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $oldImageName = $album->image;
        
        $newImage = false;
        $imageFile = $command->getImageFile();
        if ($imageFile && !$imageFile->getHasError()) {
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                $imageFile->getBaseName(),
                $imageFile->tempName,
                Settings::albumIntroImageUploadPath() . DIRECTORY_SEPARATOR . $album->id . DIRECTORY_SEPARATOR . Settings::albumIntroImageDir(),
                Settings::albumIntroImageTransformations()
            ]);
            $imageManager->extension = Settings::albumIntroImageExtension();
            $album->image = $imageManager->getOriginalImageName();
            $newImage = true;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->albumRepository->update($album);
            
            foreach($command->getTranslations() as $translation) {
                
                $albumLanguage = null;
                
                foreach ($album->translations as $translationRelation) {
                    if ((string)$translationRelation->language === (string)$translation['language']) {
                        $albumLanguage = $translationRelation;
                    }
                }
                
                if (null === $albumLanguage) {
                    $albumLanguage = $this->albumRepository->makeAlbumLangModel();
                    $albumLanguage->album_id = $album->id;
                    $albumLanguage->language = $translation['language'];
                }
                
                $albumLanguage->name = $this->textPurify($translation['name']);
                $albumLanguage->description = $this->textPurify($translation['description']);

                $this->albumRepository->update($albumLanguage);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $album;
        
        if ($newImage) {
            $imageManager->deleteAll();
            $imageManager->save();
        }
    }

    private function guardAlbumAliasIsUnique($album_alias, $id)
    {
        if ($this->albumRepository->existsByAlbumAlias($album_alias, $id)) {
            throw new LogicException('Album alias already exists.');
        }
    }
}