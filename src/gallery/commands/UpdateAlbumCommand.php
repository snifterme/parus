<?php

namespace rokorolov\parus\gallery\commands;

/**
 * UpdateAlbumCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateAlbumCommand
{
    public $model;
    
    private $id;
    private $status;
    private $album_aliase;
    private $translations;
    
    public function __construct($id, $status, $album_aliase, $translations)
    {
        $this->id = $id;
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
        return $this->id;
    }
}
