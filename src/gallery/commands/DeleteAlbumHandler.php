<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
use rokorolov\parus\gallery\repositories\PhotoRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteAlbumHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteAlbumHandler
{
    private $albumRepository;
    private $photoRepository;
    
    public function __construct(
        AlbumRepository $albumRepository,
        PhotoRepository $photoRepository
    ) {
        $this->albumRepository = $albumRepository;
        $this->photoRepository = $photoRepository;
    }
    
    public function handle(DeleteAlbumCommand $command)
    {
        if (null === $album = $this->albumRepository->findById($command->getId())) {
            throw new LogicException('Album does not exist.');
        }
        
        $photos = $this->photoRepository->findManyBy('album_id', $album->id);
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            foreach($photos as $photo) {
                $this->photoRepository->remove($photo);
            }
            $this->albumRepository->remove($album);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $config = Settings::uploadAlbumConfig($command->getId());
        $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
            null,
            null,
            Settings::uploadFilePath($album->id) . DIRECTORY_SEPARATOR . $album->id,
        ]);
        $imageManager->deleteAll();
    }
}
