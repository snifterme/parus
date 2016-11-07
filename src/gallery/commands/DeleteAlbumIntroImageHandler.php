<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteAlbumIntroImageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteAlbumIntroImageHandler
{
    private $albumRepository;
    
    public function __construct(
        AlbumRepository $albumRepository
    ) {
        $this->albumRepository = $albumRepository;
    }
    
    public function handle(DeleteAlbumIntroImageCommand $command)
    {
        if (null === $album = $this->albumRepository->findById($command->getId())) {
            throw new LogicException('Album does not exist.');
        }
        
        if (!empty($imageName = $album->image)) {
            
            $album->image = null;
            
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                null,
                null,
                Settings::albumIntroImageUploadPath() . DIRECTORY_SEPARATOR . $album->id . DIRECTORY_SEPARATOR . Settings::albumIntroImageDir(),
            ]);
            
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $this->albumRepository->update($album);
                $imageManager->deleteAll();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
}
