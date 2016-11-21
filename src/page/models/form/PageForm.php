<?php

namespace rokorolov\parus\page\models\form;

use rokorolov\parus\page\helpers\ViewHelper;
use rokorolov\parus\page\repositories\PageReadRepository;
use rokorolov\parus\page\Module;
use rokorolov\parus\page\helpers\Settings;
use rokorolov\helpers\Html;
use Yii;
use yii\base\Model;
use yii\helpers\Inflector;

/**
 * PageForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageForm extends Model
{
    public $id;
    public $status;
    public $title;
    public $slug;
    public $content;
    public $language;
    public $home;
    public $view;
    public $version;
    public $reference;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    
    public $isNewRecord = true;

    private $viewHelper;
    private $pageReadRepository;
    private $wrappedObject;
    
    public function __construct(
        ViewHelper $viewHelper,
        PageReadRepository $pageReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->pageReadRepository = $pageReadRepository;
        
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'meta_title', 'meta_keywords', 'meta_description', 'view', 'reference'], 'trim'],
            
            ['title', 'required'],
            
            [['title', 'meta_title', 'slug', 'reference'], 'string', 'max' => 128],
            
            ['status', 'required'],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],
            
            ['home', 'in', 'range' => [Settings::homePageYesSign(), Settings::homePageNoSign()]],
            
            ['slug', 'validateSlug'],

            ['language', 'required'],
            ['language', 'in', 'range' => array_keys($this->getLanguageOptions())],

            [['meta_keywords', 'meta_description', 'view'], 'string', 'max' => 255],
                        
            ['content', 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'status',
            'title',
            'slug',
            'content',
            'language',
            'home',
            'view',
            'reference',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->viewHelper->getAttributeLabels();
    }
    
    public function getStatusOptions()
    {
        return $this->viewHelper->getStatusOptions();
    }
    
    public function getCurrentStatus()
    {
        if ($this->isNewRecord) {
            return Html::a(Html::icon('circle text-info') . ' ' . Module::t('page', 'Not set'), ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
        }
        return Html::a(Html::icon('circle text-' . $this->viewHelper->getStatusHtmlType($this->status)) . ' <strong>' . $this->viewHelper->getStatusName($this->status) . '</strong>', ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
    }
    
    public function getSavedOn()
    {
        if ($this->isNewRecord) {
            return Module::t('page', 'page has not been saved yet.');
        }
        return $this->wrappedObject->updated_at_medium_with_relative(true);
    }
    
    public function getCreated_by()
    {
        return $this->wrappedObject->createdBy->username;
    }
    
    public function getUpdated_by()
    {
        return $this->wrappedObject->updatedBy->username;
    }
    
    public function getCreated_at()
    {
        return $this->wrappedObject->created_at();
    }
    
    public function getUpdated_at()
    {
        return $this->wrappedObject->updated_at();
    }

    public function getLanguageOptions()
    {
        return Settings::languageOptions();
    }
    
    public function beforeValidate()
    {
        if (empty($this->slug) && !empty($this->title)) {
            $this->slug = Inflector::slug($this->title);
        }
        return parent::beforeValidate();
    }

    public function validateSlug($attribute)
    {
        if ($this->pageReadRepository->existsBySlug($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('page', 'This slug "{value}" is already exists.', ['value' => $this->$attribute]));
        }
    }
    
    public function getData()
    {
        return $this->reverseTransform();
    }
    
    public function setData($data = null)
    {
        return $this->transform($data);
    }
    
    protected function transform($data = null)
    {
        if ($data !== null) {
            $this->isNewRecord = false;
            $this->wrappedObject = $data;
            $this->id = $this->wrappedObject->id;
            $this->status = $this->wrappedObject->status;
            $this->title = $this->wrappedObject->title;
            $this->slug = $this->wrappedObject->slug;
            $this->content = $this->wrappedObject->content;
            $this->language = $this->wrappedObject->language;
            $this->home = $this->wrappedObject->home;
            $this->view = $this->wrappedObject->view;
            $this->version = $this->wrappedObject->version;
            $this->reference = $this->wrappedObject->reference;
            $this->meta_title = $this->wrappedObject->meta_title;
            $this->meta_keywords = $this->wrappedObject->meta_keywords;
            $this->meta_description = $this->wrappedObject->meta_description;
        }
        
        if ($this->isNewRecord) {
            $this->status = $this->viewHelper->getDefaultStatus();
            $this->language = Settings::defaultLanguage();
            $this->home = Settings::homePageNoSign();
        }
        
        return $this;
    }

    protected function reverseTransform()
    {
        return $this->getAttributes();
    }
}