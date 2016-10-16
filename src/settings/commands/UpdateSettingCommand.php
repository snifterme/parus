<?php

namespace rokorolov\parus\settings\commands;

/**
 * UpdateSettingCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateSettingCommand
{
    private $param;
    private $value;
    
    public function __construct($param, $value)
    {
        $this->param = $param;
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getParam()
    {
        return $this->param;
    }
}
