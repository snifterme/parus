<?php

namespace rokorolov\parus\language\dto;

/**
 * LanguageDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguageDto
{
    public $id;
    public $title;
    public $status;
    public $order;
    public $lang_code;
    public $image;
    public $date_format;
    public $date_time_format;
    public $created_at;
    public $updated_at;
    
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
