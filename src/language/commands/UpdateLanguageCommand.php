<?php

namespace rokorolov\parus\language\commands;

/**
 * UpdateLanguageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateLanguageCommand
{
    public $model;
    
    private $id;
    private $title;
    private $status;
    private $order;
    private $lang_code;
    private $image;
    private $date_format;
    private $date_time_format;
    
    public function __construct(
        $id,
        $title,
        $status,
        $order,
        $lang_code,
        $image,
        $date_format,
        $date_time_format
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->status = $status;
        $this->order = $order;
        $this->lang_code = $lang_code;
        $this->image = $image;
        $this->date_format = $date_format;
        $this->date_time_format = $date_time_format;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
     
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function getLangCode()
    {
        return $this->lang_code;
    }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function getDateFormat()
    {
        return $this->date_format;
    }
    
    public function getDateTimeFormat()
    {
        return $this->date_time_format;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
