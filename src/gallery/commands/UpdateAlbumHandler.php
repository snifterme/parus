<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
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
        $album->album_aliase = $this->textPurify($command->getAlbumAlias());
        $album->modified_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->albumRepository->update($album);
            
            foreach($command->getTranslations() as $translation) {
                
                $albumLanguage = null;
                
                foreach ($album->translations as $translationRelation) {
                    if ($translationRelation->language === $translation['language']) {
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
    }

    private function guardAlbumAliasIsUnique($album_alias, $id)
    {
        if ($this->albumRepository->existsByAlbumAlias($album_alias, $id)) {
            throw new LogicException('Album alias already exists.');
        }
    }
}