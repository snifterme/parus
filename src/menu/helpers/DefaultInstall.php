<?php

namespace rokorolov\parus\menu\helpers;

use rokorolov\parus\menu\contracts\DefaultInstallInterface;
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
    
    public function shouldInstallDefaults()
    {
        return $this->installSettings;
    }
    
    public function getSystemRootId()
    {
        return $this->systemRootId;
    }

    public function getMenuParams()
    {
        return [
            [
                $this->systemRootId,
                1,
                'ROOT',
                0,
                0,
                '',
                '',
                Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemCode(),
                0,
                1,
                2,
            ],
        ];
    }
}
