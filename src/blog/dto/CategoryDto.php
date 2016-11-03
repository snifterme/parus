<?php

namespace rokorolov\parus\blog\dto;

/**
 * CategoryDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryDto
{
    public $id;
    public $parent_id;
    public $status;
    public $image;
    public $title;
    public $slug;
    public $description;
    public $language;
    public $depth;
    public $lft;
    public $rgt;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $created_by;
    public $created_at;
    public $modified_by;
    public $modified_at;
    public $author;
    public $posts = [];
    public $createdBy;
    public $modifiedBy;
    
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
