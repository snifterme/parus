<?php

namespace rokorolov\parus\language\helpers;

use rokorolov\parus\language\contracts\DefaultInstallInterface;

/**
 * DefaultInstall
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DefaultInstall implements DefaultInstallInterface
{
    public $installDefaults = true;
    
    private $systemId = 1;
    
    private $systemCode = 'en';

    public function shouldInstallDefaults()
    {
        return $this->installDefaults;
    }
    
    public function getSystemId()
    {
        return $this->systemId;
    }
    
    public function getSystemCode()
    {
        return $this->systemCode;
    }
    
    public function getLanguageParams()
    {
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        
        return [
            'id' => $this->systemId,
            'title' => 'English',
            'status' => 1,
            'order' => 1,
            'lang_code' => $this->systemCode,
            'image' => '',
            'date_format' => 'Y-m-d',
            'date_time_format' => 'Y-m-d H:i:s',
            'created_at' => $datetime,
            'updated_at' => $datetime,
        ];
    }
}
