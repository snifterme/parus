<?php

namespace rokorolov\parus\page\commands;

/**
 * CreatePageCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePageCommand
{
    public $model;
    
    private $status;
    private $title;
    private $slug;
    private $content;
    private $language;
    private $home;
    private $view;
    private $reference;
    private $meta_title;
    private $meta_keywords;
    private $meta_description;
    
    public function __construct(
        $status,
        $title,
        $slug,
        $content,
        $language,
        $home,
        $view,
        $reference,
        $meta_title,
        $meta_keywords,
        $meta_description
    ) {
        $this->status = $status;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->language = $language;
        $this->home = $home;
        $this->view = $view;
        $this->reference = $reference;
        $this->meta_title = $meta_title;
        $this->meta_keywords = $meta_keywords;
        $this->meta_description = $meta_description;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getHome()
    {
        return $this->home;
    }
    
    public function getView()
    {
        return $this->view;
    }
    
    public function getReference()
    {
        return $this->reference;
    }
    
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function getId()
    {
        return $this->model->id;
    }
}
