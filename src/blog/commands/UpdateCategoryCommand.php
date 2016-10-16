<?php

namespace rokorolov\parus\blog\commands;

/**
 * UpdateCategoryCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateCategoryCommand
{
    public $model;

    private $id;
    private $parent_id;
    private $status;
    private $title;
    private $slug;
    private $description;
    private $language;
    private $meta_title;
    private $meta_keywords;
    private $meta_description;
    private $imageFile;

    public function __construct(
        $id,
        $parent_id,
        $status,
        $title,
        $slug,
        $description,
        $language,
        $meta_title,
        $meta_keywords,
        $meta_description,
        $imageFile
    ) {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->status = $status;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->language = $language;
        $this->meta_title = $meta_title;
        $this->meta_keywords = $meta_keywords;
        $this->meta_description = $meta_description;
        $this->imageFile = $imageFile;
    }

    public function getParentId()
    {
        return $this->parent_id;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function getLanguage()
    {
        return $this->language;
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

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getId()
    {
        return $this->id;
    }
}
