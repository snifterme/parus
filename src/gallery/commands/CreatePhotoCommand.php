<?php

namespace rokorolov\parus\gallery\commands;

/**
 * CreatePhotoCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePhotoCommand
{
    public $model;
    
    private $album_id;
    private $status;
    private $order;
    private $imageFile;
    
    public function __construct($album_id, $status, $order, $imageFile)
    {
        $this->album_id = $album_id;
        $this->status = $status;
        $this->order = $order;
        $this->imageFile = $imageFile;
    }

    public function getAlbumId()
    {
        return $this->album_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getId()
    {
        return $this->model->id;
    }
}
