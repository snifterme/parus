<?php

namespace rokorolov\parus\menu\commands;

/**
 * CreateMenuCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateMenuCommand
{
    public $model;
    
    private $menu_type_id;
    private $status;
    private $parent_id;
    private $title;
    private $link;
    private $note;
    private $language;
    
    public function __construct(
        $menu_type_id,
        $status,
        $parent_id,
        $title,
        $link,
        $note,
        $language
    ) {
        $this->menu_type_id = $menu_type_id;
        $this->title = $title;
        $this->language = $language;
        $this->parent_id = $parent_id;
        $this->status = $status;
        $this->link = $link;
        $this->note = $note;
    }
    
    public function getMenuTypeId()
    {
        return $this->menu_type_id;
    }
    
    public function getParentId()
    {
        return $this->parent_id;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getLink()
    {
        return $this->link;
    }
    
    public function getNote()
    {
        return $this->note;
    }
    
    public function getId()
    {
        return $this->model->id;
    }
}
