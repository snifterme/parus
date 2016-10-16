<?php

namespace rokorolov\parus\gallery\commands;

/**
 * UpdatePhotoCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdatePhotoCommand
{
    public $model;
    
    private $id;
    private $status;
    private $order;
    private $album_id;
    private $translations;
    
    public function __construct($id, $status, $order, $album_id, $translations)
    {
        $this->id = $id;
        $this->status = $status;
        $this->order = $order;
        $this->album_id = $album_id;
        $this->translations = $translations;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function getAlbumId()
    {
        return $this->album_id;
    }
    public function getTranslations()
    {
        return $this->translations;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
