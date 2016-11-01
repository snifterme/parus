<?php

namespace rokorolov\parus\settings\services;

use rokorolov\parus\settings\repositories\SettingsReadRepository;
use rokorolov\parus\settings\contracts\SettingsServiceInterface;
use rokorolov\parus\settings\commands\UpdateSettingCommand;
use rokorolov\parus\settings\commands\CreateSettingCommand;
use rokorolov\parus\settings\commands\DeleteSettingCommand;

/**
 * SettingsService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsService implements SettingsServiceInterface
{
    protected $commandBus;
    protected $settingsRepository;
    
    public function __construct(
        SettingsReadRepository $settingsRepository
    ) {
        $this->settingsRepository = $settingsRepository;
    }
    
    /**
     * Get settings.
     * 
     * @return array
     */
    public function getSettings()
    {
        return $this->settingsRepository->findAllAsArray();
    }
    
    /**
     * Set settings.
     * 
     * @param string $param
     * @param mixed $value
     */
    public function setSetting($param, $value)
    {
        $this->getCommandBus()->execute(new UpdateSettingCommand(
            $param,
            $value
        ));
    }

    /**
     * Add settings.
     * 
     * @param array $params
     */
    public function addSetting(array $params)
    {
        $this->getCommandBus()->execute(new CreateSettingCommand(
            $params['param'],
            $params['value'],
            $params['type'],
            $params['order'],
            $params['translations']
        ));
    }
    
    /**
     * Remove settings.
     * 
     * @param string $param
     */
    public function deleteSetting($param)
    {
         $this->getCommandBus()->execute(new DeleteSettingCommand(
            $param
        ));
    }
    
    /**
     * Get command bus.
     * 
     * @param string $param
     */
    protected function getCommandBus()
    {
        if (null === $this->commandBus) {
            $this->commandBus = Yii::createObject('rokorolov\parus\admin\contracts\CommandBusInterface');
        }
        return $this->commandBus;
    }
    
    
}
