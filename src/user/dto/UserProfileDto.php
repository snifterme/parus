<?php

namespace rokorolov\parus\user\dto;

/**
 * UserProfileDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserProfileDto
{
    public $user_id;
    public $name;
    public $surname;
    public $language;
    public $avatar_url;
    public $last_login_on;
    public $last_login_ip;
    
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
