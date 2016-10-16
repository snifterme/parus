<?php

namespace rokorolov\parus\menu;

use rokorolov\parus\admin\helpers\Status;
use Yii;
use yii\base\BootstrapInterface;

/**
 * This is the Bootstrap of rokorolov\parus\menu\Module.
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
        Yii::$container->set('rokorolov\parus\menu\repositories\MenuTypeReadRepository', 'rokorolov\parus\menu\repositories\MenuTypeReadRepository');
        Yii::$container->set('rokorolov\parus\menu\repositories\MenuReadRepository', 'rokorolov\parus\menu\repositories\MenuReadRepository');
        Yii::$container->set('rokorolov\parus\menu\repositories\MenuTypeRepository', 'rokorolov\parus\menu\repositories\MenuTypeRepository');
        Yii::$container->set('rokorolov\parus\menu\repositories\MenuRepository', 'rokorolov\parus\menu\repositories\MenuRepository');

    }
    
    protected function registerSettings($app)
    {
        $languagesHelper = Yii::createObject('rokorolov\parus\language\helpers\LanguageHelper');
        
        Yii::$container->set('rokorolov\parus\menu\Module', [
            'languages' => ['en' => 'English'],
            'defaultLanguage' => 'en',
            'language' => 'en',
            'menuStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED,
            ],
            'menuDefaultStatus' => Status::STATUS_PUBLISHED
        ]);
    }
}
