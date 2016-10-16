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
        $this->guardAlbumAliaseIsUnique($command->getAlbumAliase());
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $album = $this->albumRepository->makeAlbumCreateModel();
        $album->status = $command->getStatus();
        $album->album_aliase = $this->textPurify($command->getAlbumAliase());
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

    private function guardAlbumAliaseIsUnique($album_aliase)
    {
        if ($this->albumRepository->existsByAlbumAliase($album_aliase)) {
            throw new LogicException('Album aliase already exists.');
        }
    }
}