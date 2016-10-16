<?php

namespace rokorolov\parus\gallery;

use rokorolov\parus\admin\helpers\Status;
use Yii;
use yii\base\BootstrapInterface;

/**
 * This is the Bootstrap of rokorolov\parus\gallery.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->resolveDependency();
        $this->registerSettings($app);
    }
    
    protected function resolveDependency()
    {
        Yii::$container->set('rokorolov\parus\gallery\repositories\AlbumReadRepository', 'rokorolov\parus\gallery\repositories\AlbumReadRepository');
        Yii::$container->set('rokorolov\parus\gallery\repositories\AlbumRepository', 'rokorolov\parus\gallery\repositories\AlbumRepository');
        Yii::$container->set('rokorolov\parus\gallery\repositories\PhotoReadRepository', 'rokorolov\parus\gallery\repositories\PhotoReadRepository');
        Yii::$container->set('rokorolov\parus\gallery\repositories\PhotoRepository', 'rokorolov\parus\gallery\repositories\PhotoRepository');
        Yii::$container->set('rokorolov\parus\gallery\contracts\GalleryServiceInterface', 'rokorolov\parus\gallery\services\GalleryService');
    }
    
    protected function registerSettings($app)
    {
        Yii::$container->set('rokorolov\parus\gallery\Module', [
            'languages' => ['en' => 'English'],
            'defaultLanguage' => 'en',
            'language' => 'en',
            'uploadImageConfig' => [
                'imagesTransformations' => [
                    ['width' => 1208, 'height' => 1208, 'method' => 'resize', 'postfix' => 'large'],
                    ['width' => 229, 'height' => 165, 'method' => 'crop', 'postfix' => 'thumb'],
                ]
            ],
            'albumStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED
            ],
            'photoStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED
            ],
            'uploadImageConfig' => [
                'maxFileSize' => 0,
                'maxImageWidth' => 0,
                'maxImageHeight' => 0,
                'minFileSize' => 0,
                'minImageWidth' => 0,
                'minImageHeight' => 0,
                'maxFileCount' => 0,
                'resizeImageQuality' => 90,
                'resizeDefaultImageExtension' => 'jpg',
                'uploadFilePath' => '@webroot/uploads/gallery',
                'uploadFileSrc' => '@web/uploads/gallery',
                'nameFileCreator' => null,
                'pathFileCreator' => null,
                'allowedExtensions' => ['jpg', 'gif', 'png', 'txt'],
                'allowedFileTypes' => ['image', 'html', 'text', 'video', 'audio', 'flash', 'object'],
                'allowedMimeTypes' => [],
                'imagesTransformations' => [],
                'previewThumbDimensions' => [98, 98],
                'previewThumbName' => 'preview-thumb',
            ],
            'albumDefaultStatus' => Status::STATUS_PUBLISHED,
            'photoDefaultStatus' => Status::STATUS_PUBLISHED
        ]);
    }
}
