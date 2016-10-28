<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
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
        $album->album_aliase = $this->textPurify($command->getAlbumAlias());
        $album->created_at = $datetime;
        $album->modified_at = $datetime;
        
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
    }

    private function guardAlbumAliasIsUnique($album_alias)
    {
        if ($this->albumRepository->existsByAlbumAlias($album_alias)) {
            throw new LogicException('Album alias already exists.');
        }
    }
}