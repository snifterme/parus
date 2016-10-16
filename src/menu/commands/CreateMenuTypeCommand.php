<?php

namespace rokorolov\parus\menu\commands;

/**
 * CreateMenuTypeCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateMenuTypeCommand
{
    public $model;
    
    private $title;
    private $menu_type_aliase;
    private $description;
    
    public function __construct($title, $menu_type_aliase, $description)
    {
        $this->title = $title;
        $this->menu_type_aliase = $menu_type_aliase;
        $this->description = $description;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getMenuTypeAliase()
    {
        return $this->menu_type_aliase;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getId()
    {
        return $this->model->id;
    }
}
