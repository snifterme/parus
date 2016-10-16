<?php

namespace rokorolov\parus\settings\dto;

/**
 * SettingsLangDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsLangDto
{
    public $settings_id;
    public $language;
    public $label;
    
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
