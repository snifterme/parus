<?php

namespace rokorolov\parus\blog\dto;

/**
 * PostDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostDto
{
    public $id;
    public $category_id;
    public $status;
    public $title;
    public $slug;
    public $introtext;
    public $fulltext;
    public $hits;
    public $image;
    public $post_type;
    public $published_at;
    public $publish_up;
    public $publish_down;
    public $language;
    public $view;
    public $version;
    public $reference;
    public $created_by;
    public $created_at;
    public $modified_by;
    public $modified_at;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $deleted_at;
    public $category;
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
