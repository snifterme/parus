<?php

namespace rokorolov\parus\gallery\dto;

/**
 * PhotoDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoDto
{
    public $id;
    public $status;
    public $order;
    public $album_id;
    public $photo_name;
    public $photo_size;
    public $photo_extension;
    public $photo_mime;
    public $photo_path;
    public $created_at;
    public $modified_at;
    public $translation;
    public $translations = [];
    
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
