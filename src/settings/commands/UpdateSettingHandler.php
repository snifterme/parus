<?php

namespace rokorolov\parus\settings\commands;

use rokorolov\parus\settings\repositories\SettingsRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * UpdateSettingHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateSettingHandler
{
    private $settingsRepository;
    
    public function __construct(
        SettingsRepository $settingsRepository
    ) {
        $this->settingsRepository = $settingsRepository;
    }
    
    public function handle(UpdateSettingCommand $command)
    {
        if (null === $setting = $this->settingsRepository->findFirstBy('param', $command->getParam())) {
            throw new LogicException('Setting does not exist.');
        }
        
        $setting->value = $command->getValue();
        $this->settingsRepository->update($setting);
    }
}