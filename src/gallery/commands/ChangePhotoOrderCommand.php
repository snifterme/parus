<?php

namespace rokorolov\parus\gallery\commands;

/**
 * ChangePhotoOrderCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePhotoOrderCommand
{
    private $album;
    private $order;
    
    public function __construct(array $order)
    {
//        $this->album = $album;
        $this->order = $order;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function getAlbum()
    {
        return $this->album;
    }
}
