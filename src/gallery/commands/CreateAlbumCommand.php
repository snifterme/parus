<?php

namespace rokorolov\parus\gallery\commands;

/**
 * CreateAlbumCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateAlbumCommand
{
    public $model;
    
    private $status;
    private $album_aliase;
    private $translations;
    
    public function __construct($status, $album_aliase, $translations)
    {
        $this->status = $status;
        $this->album_aliase = $album_aliase;
        $this->translations = $translations;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getAlbumAliase()
    {
        return $this->album_aliase;
    }
    
    public function getTranslations()
    {
        return $this->translations;
    }
    
    public function getId()
    {
        return $this->model->id;
    }
}
