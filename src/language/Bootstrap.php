<?php

namespace rokorolov\parus\language;

use rokorolov\parus\admin\helpers\Status;
use Yii;
use yii\base\BootstrapInterface;

/**
 * This is the Bootstrap of rokorolov\parus\language\Module.
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
        $this->registerSettings();
    }
    
    protected function resolveDependency()
    {
        Yii::$container->set('rokorolov\parus\language\repositories\LanguageRepository', 'rokorolov\parus\language\repositories\LanguageRepository');
        Yii::$container->set('rokorolov\parus\language\repositories\LanguageReadRepository', 'rokorolov\parus\language\repositories\LanguageReadRepository');
    }
    
    protected function registerSettings()
    {
        Yii::$container->set('rokorolov\parus\language\Module', [
            'defaultAppLanguage' => 'en',
            'languageStatuses' => [
                Status::STATUS_PUBLISHED,
                Status::STATUS_UNPUBLISHED,
            ],
            'languageDefaultStatus' => Status::STATUS_PUBLISHED
        ]);
    }
}
