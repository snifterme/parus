<?php

namespace rokorolov\parus\settings\commands;

/**
 * CreateSettingCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateSettingCommand
{
    public $model;
    
    private $param;
    private $value;
    private $type;
    private $order;
    private $translations;
    
    public function __construct($param, $value, $type, $order, $translations)
    {
        $this->param = $param;
        $this->value = $value;
        $this->type = $type;
        $this->order = $order;
        $this->translations = $translations;
    }
    
    public function getParam()
    {
        return $this->param;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function getTranslations()
    {
        return $this->translations;
    }
    
    public function getId()
    {
        return $this->model->id;
    }
}
