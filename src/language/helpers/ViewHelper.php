<?php

namespace rokorolov\parus\language\helpers;

use rokorolov\parus\language\Module;
use rokorolov\parus\language\helpers\Settings;
use Yii;
use yii\helpers\Inflector;

/**
 * ViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ViewHelper
{
    private $statusService;
    
    public function getStatusOptions()
    {
        return $this->getStatusService()->getStatusOptions();
    }
    
    public function getStatuses()
    {
        return $this->getStatusService()->getStatuses();
    }
    
    public function getStatusActions()
    {
        return $this->getStatusService()->getStatusActions();
    }

    public function getAttributeLabel($attributeName)
    {
        return isset($this->getAttributeLabels()[$attributeName]) ? $this->getAttributeLabels()[$attributeName] : Inflector::camel2words($attributeName, true);
    }

    public function getDefaultStatus()
    {
        return $this->getStatusService()->getStatus();
    }

    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('language', 'Id'),
            'title' => Module::t('language', 'Title'),
            'status' => Module::t('language', 'Status'),
            'order' => Module::t('language', 'Order'),
            'lang_code' => Module::t('language', 'Language code'),
            'image' => Module::t('language', 'Flag'),
            'date_format' => Module::t('language', 'Date Format'),
            'date_time_format' => Module::t('language', 'Date Time Format'),
            'created_at' => Module::t('language', 'Created on'),
            'modified_at' => Module::t('language', 'Last edited on'),
        ];
    }
    
    /**
     * 
     * @return type
     */
    protected function getStatusService()
    {
        if ($this->statusService === null) {
            $this->statusService = Yii::createObject('StatusService', [
                Settings::languageStatuses(),
                Settings::languageDefaultStatus(),
            ]);
        }
        
        return $this->statusService;
    }
}
