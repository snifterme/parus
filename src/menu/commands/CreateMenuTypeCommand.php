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
    private $menu_type_alias;
    private $description;
    
    public function __construct($title, $menu_type_alias, $description)
    {
        $this->title = $title;
        $this->menu_type_alias = $menu_type_alias;
        $this->description = $description;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getMenuTypeAlias()
    {
        return $this->menu_type_alias;
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
