<?php

namespace rokorolov\parus\settings\commands;

use rokorolov\parus\settings\repositories\SettingsRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteSettingHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteSettingHandler
{
    private $settingsRepository;
    
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }
    
    public function handle(DeleteSettingCommand $command)
    {
        if (null === $setting = $this->settingsRepository->findFirstBy('param', $command->getParam())) {
            throw new LogicException('Setting does not exist.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->settingsRepository->remove($setting);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
