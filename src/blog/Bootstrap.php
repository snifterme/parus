<?php

namespace rokorolov\parus\blog;

use rokorolov\parus\admin\helpers\Status;
use Yii;
use yii\base\BootstrapInterface;

/**
 * This is the Bootstrap of rokorolov\parus\blog\Module.
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
        Yii::$container->set('rokorolov\parus\blog\repositories\PostReadRepository', 'rokorolov\parus\blog\repositories\PostReadRepository');
        Yii::$container->set('rokorolov\parus\blog\repositories\CategoryReadRepository', 'rokorolov\parus\blog\repositories\CategoryReadRepository');
        Yii::$container->set('rokorolov\parus\blog\repositories\PostRepository', 'rokorolov\parus\blog\repositories\PostRepository');
        Yii::$container->set('rokorolov\parus\blog\repositories\CategoryRepository', 'rokorolov\parus\blog\repositories\CategoryRepository');
    }
    
    protected function registerSettings($app)
    {
        Yii::$container->set('rokorolov\parus\blog\Module', [
            'languages' => ['en' => 'English'],
            'defaultLanguage' => 'en',
            'language' => 'en',
            'postImageUploadConfig' => [
                'introImageUploadPath' => Yii::getAlias('@introImagesPostPath'),
                'introImageUploadSrc' => Yii::getAlias('@introImagesPostUrl'),
                'imageUploadPath' => Yii::getAlias('@imageUploadPath'),
                'imageUploadSrc' => Yii::getAlias('@imageUploadUrl'),
                'imageTransformations' => []
            ],
            'categoryImageUploadConfig' => [
                'introImageUploadPath' => Yii::getAlias('@introImagesCategoryPath'),
                'introImageUploadSrc' => Yii::getAlias('@introImagesCategoryUrl'),
                'imageUploadPath' => Yii::getAlias('@imageUploadPath'),
                'imageUploadSrc' => Yii::getAlias('@imageUploadUrl'),
                'imageTransformations' => []
            ],
            'postStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED,
                Status::STATUS_DRAFT,
                Status::STATUS_ARCHIVED,
            ],
            'categoryStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED,
            ],
            'postDefaultStatus' => Status::STATUS_PUBLISHED,
            'categoryDefaultStatus' => Status::STATUS_PUBLISHED
        ]);
    }
}
