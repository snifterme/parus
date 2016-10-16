<?php

namespace rokorolov\parus\gallery\dto;

/**
 * AlbumManageDto
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumManageDto
{
    public $id;
    public $status;
    public $name;
    public $photo_count;
    
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
