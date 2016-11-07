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
    private $album_alias;
    private $translations;
    private $imageFile;
    
    public function __construct(
        $id,
        $status,
        $album_alias,
        $translations,
        $imageFile
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->album_alias = $album_alias;
        $this->translations = $translations;
        $this->imageFile = $imageFile;
    }
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getAlbumAlias()
    {
        return $this->album_alias;
    }
    
    public function getTranslations()
    {
        return $this->translations;
    }
    
    public function getImageFile()
    {
        return $this->imageFile;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
