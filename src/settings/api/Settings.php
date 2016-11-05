<?php

namespace rokorolov\parus\settings\api;

use rokorolov\parus\admin\base\BaseApi;
use Yii;

/**
 * Settings
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Settings extends BaseApi
{
    public $settingsComponent;
    
    public function getByKey($key)
    {
        return $this->getSettingsComponent()->get($key);
    }
    
    public function getAll()
    {
        return $this->getSettingsComponent()->getAll();
    }
    
    public function getSettingsComponent()
    {
        if (null === $this->settingsComponent) {
            Yii::$container->set('rokorolov\parus\settings\contracts\SettingsServiceInterface', 'rokorolov\parus\settings\services\SettingsService');
            $this->settingsComponent = Yii::createObject('rokorolov\parus\settings\components\SettingsComponent');
        }
        return $this->settingsComponent;
    }
}
