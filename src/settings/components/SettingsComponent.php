<?php

namespace rokorolov\parus\settings\components;

use rokorolov\parus\settings\contracts\SettingsServiceComponentInterface;
use rokorolov\parus\settings\helpers\Settings;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\Cache;
use yii\base\Component;
use yii\caching\TagDependency;
use yii\base\InvalidParamException;

 /**
 * This is the SettingsComponent component.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsComponent extends Component
{
    /**
     *
     * @var string 
     */
    public $cache = 'cache';
    
    /**
     *
     * @var mixed 
     */
    private $_data = null;
    
    /**
     *
     * @var type 
     */
    private $service;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        SettingsServiceComponentInterface $service,
        $config = array()
    ) {
        $this->service = $service;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }
    }
    
    /**
     * Get settings by key.
     * 
     * @param string $key
     * @return mixed
     * @throws InvalidParamException
     */
    public function get($key)
    {
        $data = $this->getSettings();
        
        if (array_key_exists($key, $data)){
            return $data[$key];
        } else {
            throw new InvalidParamException('Undefined parameter ' . $key);
        }
    }
    
    /**
     * Get all settings
     * 
     * @return mixed
     */
    public function getAll()
    {
        return $this->getSettings();
    }
    
    /**
     * Set settings.
     * 
     * @param string $key
     * @param mixed $value
     * @return type
     */
    public function set($key, $value)
    {
        if ($this->service->setSetting($key, $value)) {
            return $this->clearCache();
        }
    }
    
    /**
     * Add settings.
     * 
     * @param type $params
     */
    public function add(array $params) 
    {
        !isset($params[0]) && $params = [$params];
        foreach ($params as $param) {
            $this->service->addSetting($param);
        }
        $this->clearCache();
    }
    
    /**
     * Delete settings.
     * 
     * @param mixed $params
     */
    public function delete($params)
    {
        !is_array($params) && $params = [$params];
        foreach ($params as $param) {
            $this->service->deleteSetting($param);
        }
        $this->clearCache();
    }
    
    /**
     * Remove settings from cache.
     * 
     * @return boolean
     */
    public function clearCache()
    {
        $this->_data = null;
        
        if ($this->cache instanceof Cache) {
            return $this->cache->delete($this->getCacheKey());
        }
        return true;
    }
    
    /**
     * Get settings.
     * 
     * @return array
     */
    protected function getSettings()
    {
        if (null === $this->_data) {
            $cacheKey = $this->getCacheKey();
            if (false === $settings = Yii::$app->cache->get($cacheKey)) {
                $settingsRepository = Yii::createObject('rokorolov\parus\settings\repositories\SettingsReadRepository');
                if (null === $settings = $this->service->getSettings()) {
                    throw new NotFoundHttpException;
                }
                $settings = ArrayHelper::map($settings, 'param', 'value');
                Yii::$app->cache->set(
                    $cacheKey,
                    $settings,
                    86400,
                    new TagDependency(
                        [
                            'tags' => [
                                TagDependencyNamingHelper::getCommonTag(Settings::settingsDependencyTagName()),
                            ]
                        ]
                    )
                );
            }
            $this->_data = $settings;
        }
        return $this->_data;
    }
    
    protected function getCacheKey()
    {
        return static::class;
    }
}
