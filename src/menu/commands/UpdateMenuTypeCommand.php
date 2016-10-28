<?php

namespace rokorolov\parus\menu\commands;

/**
 * UpdateMenuTypeCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateMenuTypeCommand
{
    public $model;
    
    private $id;
    private $title;
    private $menu_type_alias;
    private $description;
    
    public function __construct($id, $title, $menu_type_alias, $description)
    {
        $this->id = $id;
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
        return $this->id;
    }
}
