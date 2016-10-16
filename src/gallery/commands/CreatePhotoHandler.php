<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\PhotoRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\services\ImageManager;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreatePhotoHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePhotoHandler
{
    private $photoRepository;
    
    public function __construct(
        PhotoRepository $photoRepository
    ) {
        $this->photoRepository = $photoRepository;
    }
    
    public function handle(CreatePhotoCommand $command)
    {
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $photo = $this->photoRepository->makePhotoCreateModel();
        $photo->status = $command->getStatus();
        $photo->album_id = $command->getAlbumId();
        $photo->order = $command->getOrder();
        $photo->created_at = $datetime;
        $photo->modified_at = $datetime;
        
        $imageFile = $command->getImageFile();
        
        if (!$imageFile || $imageFile->getHasError()) {
            throw new LogicException("Image not exist or file has errors '$imageFile->error'.");
        }
        
        $config = Settings::uploadAlbumConfig($command->getAlbumId());
        
        $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
            $imageFile->getBaseName(),
            $imageFile->tempName,
            Settings::uploadFilePath($command->getAlbumId()) . DIRECTORY_SEPARATOR . $command->getAlbumId(),
            array_merge($config['imagesTransformations'], [
                [
                    'width' => $config['previewThumbDimensions'][0],
                    'height' => $config['previewThumbDimensions'][1],
                    'method' => 'crop',
                    'postfix' => $config['previewThumbName']
                ]
            ])
        ]);
        $imageManager->extension = $config['resizeDefaultImageExtension'];
        $imageManager->ensureUnique = true;
        $imageManager->response = true;
        
        $response = $imageManager->save();
        $original = $response[ImageManager::ORIGIN_IMAGE_KEY];
        
        $photo->photo_name = $original['image']['name'];
        $photo->photo_size = $original['image']['size'];
        $photo->photo_extension = $original['image']['extension'];
        $photo->photo_mime = $original['image']['mime'];
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->photoRepository->add($photo);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $imageManager->delete();
            throw $e;
        }
        
        $command->model = $photo;
    }
}