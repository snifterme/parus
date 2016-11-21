<?php

namespace rokorolov\parus\gallery\helpers;

use rokorolov\parus\gallery\Module;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\gallery\helpers\ViewHelper;
use Yii;

/**
 * PhotoViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoViewHelper extends ViewHelper
{
    private $statusService;
    
    /**
     * 
     * @return array
     */
    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('gallery', 'ID'),
            'status' => Module::t('gallery', 'Status'),
            'gallery_id' => Module::t('gallery', 'Gallery ID'),
            'photo_name' => Module::t('gallery', 'Photo Name'),
            'photo_size' => Module::t('gallery', 'Photo Size'),
            'photo_extension' => Module::t('gallery', 'Photo Extension'),
            'photo_mime' => Module::t('gallery', 'Photo Mime'),
            'photo_path' => Module::t('gallery', 'Photo Path'),
            'created_at' => Module::t('gallery', 'Created on'),
            'updated_at' => Module::t('gallery', 'Last edited on'),
            'photo_id' => Module::t('gallery', 'Photo ID'),
            'caption' => Module::t('gallery', 'Caption'),
            'language' => Module::t('gallery', 'Language'),
        ];
    }
    
    /**
     * 
     * @return type
     */
    public function getDefaultStatus()
    {
        return $this->getStatusService()->getStatus();
    }
    
    /**
     * 
     * @return type
     */
    protected function getStatusService()
    {
        if ($this->statusService === null) {
            $this->statusService = Yii::createObject('StatusService', [
                Settings::photoStatuses(),
                Settings::photoDefaultStatus()
            ]);
        }
        
        return $this->statusService;
    }
}
