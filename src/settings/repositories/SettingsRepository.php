<?php

namespace rokorolov\parus\settings\repositories;

use rokorolov\parus\admin\base\BaseRepository;
use rokorolov\parus\settings\models\Settings;
use Yii;

/**
 * SettingsRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsRepository extends BaseRepository
{
    public function makeSettingsCreateModel()
    {
        return $this->getModel();
    }
    
    public function makeSettingsLangModel()
    {
        return Yii::createObject('rokorolov\parus\settings\models\SettingsLang');
    }
    
    public function existsByParam($param)
    {
        $exist = $this->model->find()
            ->where(['param' => $param])
            ->exists();

        return $exist;
    }
    
    public function model()
    {
        return Settings::className();
    }
}
