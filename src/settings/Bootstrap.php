<?php

namespace rokorolov\parus\settings;

use Yii;
use yii\base\BootstrapInterface;

/**
 * This is the Bootstrap of rokorolov\parus\settings\Module.
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
        Yii::$container->set('SettingsService', 'rokorolov\parus\settings\services\SettingsService');
        Yii::$container->set('rokorolov\parus\settings\repositories\SettingsRepository', 'rokorolov\parus\settings\repositories\SettingsRepository');
        Yii::$container->set('rokorolov\parus\settings\repositories\SettingsReadRepository', 'rokorolov\parus\settings\repositories\SettingsReadRepository');
        Yii::$container->set('rokorolov\parus\settings\contracts\SettingsServiceInterface', 'rokorolov\parus\settings\services\SettingsService');
    }
    
    protected function registerSettings($app)
    {
        Yii::$container->set('rokorolov\parus\settings\Module', [
            'language' => 'en',
            'configuration' => [
                'SITE.DEFAULT_LANGUAGE' => [
                    'items' => ['en' => 'English'],
                ]
            ]
        ]);
    }
}
