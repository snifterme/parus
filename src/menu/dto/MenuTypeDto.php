<?php

namespace rokorolov\parus\menu\dto;

/**
 * MenuTypeDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeDto
{
    public $id;
    public $title;
    public $menu_type_alias;
    public $description;
    public $menu;
    
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
