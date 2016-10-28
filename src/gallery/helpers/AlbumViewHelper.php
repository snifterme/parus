<?php

namespace rokorolov\parus\gallery\helpers;

use rokorolov\parus\gallery\Module;
use rokorolov\parus\gallery\helpers\ViewHelper;
use rokorolov\parus\gallery\helpers\Settings;
use Yii;

/**
 * AlbumViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumViewHelper extends ViewHelper
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
            'created_at' => Module::t('gallery', 'Created on'),
            'modified_at' => Module::t('gallery', 'Last edited on'),
            'photo_count' => Module::t('gallery', 'Photos'),
            'gallery_id' => Module::t('gallery', 'Gallery ID'),
            'name' => Module::t('gallery', 'Name'),
            'description' => Module::t('gallery', 'Description'),
            'language' => Module::t('gallery', 'Language'),
            'album_alias' => Module::t('gallery', 'Album alias'),
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
                Settings::albumStatuses(),
                Settings::albumDefaultStatus()
            ]);
        }
        
        return $this->statusService;
    }
}
