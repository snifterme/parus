<?php

namespace rokorolov\parus\gallery\dto;

/**
 * PhotoLangDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoLangDto
{
    public $photo_id;
    public $language;
    public $caption;
    public $description;
    
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
