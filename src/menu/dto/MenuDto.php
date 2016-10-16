<?php

namespace rokorolov\parus\menu\dto;

/**
 * MenuDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuDto
{
    public $id;
    public $menu_type_id;
    public $status;
    public $title;
    public $link;
    public $note;
    public $parent_id;
    public $language;
    public $depth;
    public $lft;
    public $rgt;
    public $menuType;
    
    public function __construct(array &$data, $prefix = null, $unset = true)
    {
        foreach ($data as $key => $value) {
            if ($prefix) {
                if (strpos($key, $prefix . '_') === false) {
                    continue;
                }
                $attribute = str_replace($prefix . '_', '', $key);
                if (property_exists($this, $attribute)) {
                    $this->$attribute = $value;
                    if ($unset) {
                        unset($data[$key]);
                    }
                }
            } else {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            } 
        }
    }
}
