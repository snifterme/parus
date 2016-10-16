<?php

namespace rokorolov\parus\settings\commands;

use rokorolov\parus\settings\repositories\SettingsRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreateSettingHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateSettingHandler
{
    private $settingsRepository;
    
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function handle(CreateSettingCommand $command)
    {
        $this->guardParamIsUnique($command->getParam());
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        
        $settings = $this->settingsRepository->makeSettingsCreateModel();
        $settings->param = $command->getParam();
        $settings->value = $command->getValue();
        $settings->type = $command->getType();
        $settings->order = $command->getOrder();
        $settings->created_at = $datetime;
        $settings->modified_at = $datetime;
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->settingsRepository->add($settings);
            
            foreach($command->getTranslations() as $translation) {

                $settingsLanguage = $this->settingsRepository->makeSettingsLangModel();
                $settingsLanguage->settings_id = $settings->id;
                $settingsLanguage->language = $translation['language'];
                $settingsLanguage->label = $translation['label'];

                $this->settingsRepository->update($settingsLanguage);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $settings;
    }
    
    private function guardParamIsUnique($param)
    {
        if ($this->settingsRepository->existsByParam($param)) {
            throw new LogicException("Param '$param' already exists");
        }
    }
}