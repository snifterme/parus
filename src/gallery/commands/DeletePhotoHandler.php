<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\PhotoRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeletePhotoHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePhotoHandler
{
    private $photoRepository;
    
    public function __construct(
        PhotoRepository $photoRepository
    ) {
        $this->photoRepository = $photoRepository;
    }
    
    public function handle(DeletePhotoCommand $command)
    {
        if (null === $photo = $this->photoRepository->findById($command->getId())) {
            throw new LogicException('Photo does not exist.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->photoRepository->remove($photo);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $album = $photo->album_id;
        $config = Settings::uploadAlbumConfig($album);
        $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
            null,
            null,
            Settings::uploadFilePath($album) . DIRECTORY_SEPARATOR . $album,
            array_merge($config['imageTransformations'], [
                [
                    'postfix' => $config['previewThumbName']
                ]
            ])
        ]);
        $imageManager->setOriginalImageName($photo->photo_name);
        $imageManager->delete();
    }
}
