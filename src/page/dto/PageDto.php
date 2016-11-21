<?php

namespace rokorolov\parus\page\dto;

/**
 * PageDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageDto
{
    public $id;
    public $status;
    public $title;
    public $slug;
    public $content;
    public $hits;
    public $home;
    public $view;
    public $version;
    public $reference;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;
    public $language;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $deleted_at;
    public $author;
    public $createdBy;
    public $updatedBy;
    
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
