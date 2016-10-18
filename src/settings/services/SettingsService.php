<?php

namespace rokorolov\parus\settings\services;

use rokorolov\parus\settings\repositories\SettingsReadRepository;
use rokorolov\parus\settings\contracts\SettingsServiceInterface;
use rokorolov\parus\settings\commands\UpdateSettingCommand;
use rokorolov\parus\settings\commands\CreateSettingCommand;
use rokorolov\parus\settings\commands\DeleteSettingCommand;
use rokorolov\parus\admin\contracts\CommandBusInterface;

/**
 * SettingsService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsService implements SettingsServiceInterface
{
    private $commandBus;
    protected $settingsRepository;
    
    public function __construct(
        CommandBusInterface $commandBus,
        SettingsReadRepository $settingsRepository
    ) {
        $this->commandBus = $commandBus;
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
     * @param string $key
     * @param mixed $value
     */
    public function setSetting($param, $value)
    {
        $this->commandBus->execute(new UpdateSettingCommand(
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
        $this->commandBus->execute(new CreateSettingCommand(
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
         $this->commandBus->execute(new DeleteSettingCommand(
            $param
        ));
    }
}
