<?php

namespace rokorolov\parus\page;

use rokorolov\parus\admin\helpers\Status;
use Yii;
use yii\base\BootstrapInterface;

/**
 * This is the Bootstrap of rokorolov\parus\page\Module.
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
        Yii::$container->set('rokorolov\parus\page\repositories\PageReadRepository', 'rokorolov\parus\page\repositories\PageReadRepository');
        Yii::$container->set('rokorolov\parus\page\repositories\PageRepository', 'rokorolov\parus\page\repositories\PageRepository');
    }
    
    protected function registerSettings($app)
    {
        $languagesHelper = Yii::createObject('rokorolov\parus\language\helpers\LanguageHelper');
        
        Yii::$container->set('rokorolov\parus\page\Module', [
            'languages' => ['en' => 'English'],
            'defaultLanguage' => 'en',
            'language' => 'en',
            'imageUploadPath' => Yii::getAlias('@imageUploadPath'),
            'imageUploadSrc' => Yii::getAlias('@imageUploadUrl'),
            'pageStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED,
                Status::STATUS_DRAFT,
                Status::STATUS_ARCHIVED,
            ],
            'pageDefaultStatus' => Status::STATUS_PUBLISHED,
        ]);
    }
}
