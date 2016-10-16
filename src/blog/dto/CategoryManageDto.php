<?php

namespace rokorolov\parus\blog\dto;

/**
 * CategoryManageDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryManageDto
{
    public $id;
    public $title;
    public $user_username;
    public $status;
    public $depth;
    public $created_at;
    public $created_by;
    
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
