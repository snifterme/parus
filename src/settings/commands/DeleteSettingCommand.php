<?php

namespace rokorolov\parus\settings\commands;

/**
 * DeleteSettingCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteSettingCommand
{
    private $param;
    
    public function __construct($param)
    {
        $this->param = $param;
    }
    
    public function getParam()
    {
        return $this->param;
    }
}
