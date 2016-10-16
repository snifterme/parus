<?php

namespace rokorolov\parus\page\helpers;

use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\page\Module;
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
    
    public function getStatusName($status = null)
    {
        return $this->getStatusService()->getStatusName($status);
    }
    
    public function getStatusHtmlType($status = null)
    {
        return $this->getStatusService()->getStatusHtmlType($status);
    }
    
    public function getDefaultStatus()
    {
        return $this->getStatusService()->getStatus();
    }
    
    public function getDefaultLanguage()
    {
        return Settings::defaultLanguage();
    }
    
    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('page', 'Id'),
            'status' => Module::t('page', 'Status'),
            'hits' => Module::t('page', 'Hits'),
            'created_by' => Module::t('page', 'Author'),
            'modified_by' => Module::t('page', 'Last edited by'),
            'created_at' => Module::t('page', 'Created on'),
            'modified_at' => Module::t('page', 'Last edited on'),
            'title' => Module::t('page', 'Title'),
            'slug' => Module::t('page', 'Slug'),
            'content' => Module::t('page', 'Content'),
            'home' => Module::t('page', 'Home'),
            'view' => Module::t('page', 'View'),
            'reference' => Module::t('page', 'Reference'),
            'version' => Module::t('page', 'Version'),
            'language' => Module::t('page', 'Language'),
            'meta_title' => Module::t('page', 'Meta Title'),
            'meta_keywords' => Module::t('page', 'Meta Keywords'),
            'meta_description' => Module::t('page', 'Meta Description'),
            'user_username' => Module::t('page', 'Author'),
        ];
    }
    
    protected function getStatusService()
    {
        if ($this->statusService === null) {
            $this->statusService = Yii::createObject('StatusService', [
                Settings::pageStatuses(),
                Settings::pageDefaultStatus()
            ]);
        }
        
        return $this->statusService;
    }
}
