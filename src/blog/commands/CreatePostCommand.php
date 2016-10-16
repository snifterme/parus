<?php

namespace rokorolov\parus\blog\commands;

/**
 * CreatePostCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePostCommand
{
    public $model;
    
    private $category_id;
    private $status;
    private $title;
    private $slug;
    private $introtext;
    private $fulltext;
    private $language;
    private $view;
    private $reference;
    private $meta_title;
    private $meta_keywords;
    private $meta_description;
    private $published_at;
    private $publish_up;
    private $publish_down;
    private $imageFile;
    
    public function __construct(
        $category_id,
        $status,
        $title,
        $slug,
        $introtext,
        $fulltext,
        $language,
        $published_at,
        $publish_up,
        $publish_down,
        $view,
        $reference,
        $meta_title,
        $meta_keywords,
        $meta_description,
        $imageFile
    ) {
        $this->category_id = $category_id;
        $this->status = $status;
        $this->title = $title;
        $this->slug = $slug;
        $this->introtext = $introtext;
        $this->fulltext = $fulltext;
        $this->language = $language;
        $this->published_at = $published_at;
        $this->publish_up = $publish_up;
        $this->publish_down = $publish_down;
        $this->view = $view;
        $this->reference = $reference;
        $this->meta_title = $meta_title;
        $this->meta_keywords = $meta_keywords;
        $this->meta_description = $meta_description;
        $this->imageFile = $imageFile;
    }
    
    public function getCategoryId()
    {
        return $this->category_id;
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
    
    public function getIntrotext()
    {
        return $this->introtext;
    }
    
    public function getFulltext()
    {
        return $this->fulltext;
    }
    
    public function getLanguage()
    {
        return $this->language;
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
    
    public function getPublishedAt()
    {
        return $this->published_at;
    }
    
    public function getPublishUp()
    {
        return $this->publish_up;
    }
    
    public function getPublishDown()
    {
        return $this->publish_down;
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
