<?php

namespace rokorolov\parus\blog\helpers;

use rokorolov\parus\blog\contracts\DefaultInstallInterface;
use Yii;

/**
 * DefaultInstall
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DefaultInstall implements DefaultInstallInterface
{
    public $installSettings = true;
    
    private $systemRootId = 1;
    
    private $systemDefaultId = 2;
    
    public function shouldInstallDefaults()
    {
        return $this->installSettings;
    }
    
    public function getSystemRootId()
    {
        return $this->systemRootId;
    }
    
    public function getSystemDefaultId()
    {
        return $this->systemDefaultId;
    }
    
    public function getCategoryParams()
    {
        $userId = Yii::createObject('rokorolov\parus\user\helpers\DefaultInstall')->getSystemId();
        $language = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemId();
        $datetime = (new \DateTime());
        
        return [
            [
                'id' => $this->systemRootId,
                'status' => 1,
                'parent_id' => 0,
                'image' => null,
                'title' => 'ROOT',
                'slug' => 'root',
                'description' => '',
                'created_by' => $userId,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'modified_by' => $userId,
                'modified_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'depth' => 0,
                'lft' => 1,
                'rgt' => 4,
                'language' => $language,
                'reference'  => null,
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => '',
            ],
            [
                'id' => $this->systemDefaultId,
                'status' => 1,
                'parent_id' => $this->systemRootId,
                'image' => null,
                'title' => 'Uncategorised',
                'slug' => 'uncategorised',
                'description' => '',
                'created_by' => $userId,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'modified_by' => $userId,
                'modified_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'depth' => 1,
                'lft' => 2,
                'rgt' => 3,
                'language' => $language,
                'reference'  => null,
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => '',
            ]
        ];
    }

}
