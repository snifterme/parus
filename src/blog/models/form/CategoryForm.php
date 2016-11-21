<?php

namespace rokorolov\parus\blog\models\form;

use rokorolov\parus\blog\helpers\CategoryViewHelper;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\repositories\CategoryReadRepository;
use rokorolov\parus\blog\Module;
use rokorolov\helpers\Html;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Inflector;

/**
 * CategoryForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryForm extends Model
{
    public $id;
    public $parent_id;
    public $status;
    public $language;
    public $title;
    public $slug;
    public $description;
    public $depth;
    public $lft;
    public $rgt;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;

    public $isNewRecord = true;
    public $imageFile;

    private $viewHelper;
    private $categoryReadRepository;
    private $wrappedObject;

    public function __construct(
        CategoryViewHelper $viewHelper,
        CategoryReadRepository $categoryReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->categoryReadRepository = $categoryReadRepository;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['title', 'slug', 'meta_title', 'meta_keywords', 'meta_description'], 'trim'],

            ['title', 'required'],

            ['status', 'required'],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],
            ['status', 'default', 'value' => $this->viewHelper->getDefaultStatus()],

            ['parent_id', 'required'],
            ['parent_id', 'integer'],

            [['title', 'slug'], 'string', 'max' => 128],

            ['language', 'required'],
            ['language', 'in', 'range' => array_keys($this->getLanguageOptions())],

            ['slug', 'validateSlug'],

            [['meta_keywords', 'meta_description'], 'string', 'max' => 255],

            [['description'], 'safe'],

            ['imageFile', 'file', 'skipOnEmpty' => true,
                'extensions' => implode(',', Settings::categoryIntroImageAllowedExtensions()),
                'mimeTypes' => implode(',', Settings::categoryIntroImageAllowedMimeTypes()),
                'minSize' => Settings::categoryIntroImageMinSize(),
                'maxSize' => Settings::categoryIntroImageMaxSize()
            ],
            
            ['imageFile', 'image', 'skipOnEmpty' => true,
                'minWidth' => Settings::categoryIntroImageMinWidth(),
                'maxWidth' => Settings::categoryIntroImageMaxWidth(),
                'minHeight' => Settings::categoryIntroImageMinHeight(),
                'maxHeight' => Settings::categoryIntroImageMaxHeight()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'parent_id',
            'status',
            'title',
            'slug',
            'description',
            'language',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'imageFile'
        ];
    }

    public function attributeLabels()
    {
        return $this->viewHelper->getAttributeLabels();
    }

    public function getStatusOptions()
    {
        return $this->viewHelper->getStatusOptions();
    }

    public function getParentOptions()
    {
        $categories = $this->categoryReadRepository->findChildrenListAsArray(1, null, $this->lft, $this->rgt);

        return $this->viewHelper->transformCategoryForOptions($categories);
    }

    public function getImagePreview()
    {
        if (!$this->isNewRecord && null !== $image = $this->wrappedObject->image_preview()) {
            return Html::img($image, ['class' => 'file-preview-image img-responsive', 'alt' => $this->wrappedObject->image, 'title' => $this->wrappedObject->image]);
        }
        return null;
    }

    public function getImageOriginal()
    {
        if (!$this->isNewRecord && null !== $image = $this->wrappedObject->image_original()) {
            return $image;
        }
        return null;
    }

    public function getCurrentStatus()
    {
        if ($this->isNewRecord) {
            return Html::a(Html::icon('circle text-info') . ' ' . Module::t('blog', 'Not set'), ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
        }
        return Html::a(Html::icon('circle text-' . $this->viewHelper->getStatusHtmlType($this->status)) . ' <strong>' . $this->viewHelper->getStatusName($this->status) . '</strong>', ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
    }

    public function getSavedOn()
    {
        if ($this->isNewRecord) {
            return Module::t('blog', 'post has not been saved yet.');
        }

        return $this->wrappedObject->modified_at_medium_with_relative(true);
    }

    public function getCreated_by()
    {
        return $this->wrappedObject->createdBy->username;
    }

    public function getModified_by()
    {
        return $this->wrappedObject->modifiedBy->username;
    }

    public function getCreated_at()
    {
        return $this->wrappedObject->created_at();
    }

    public function getModified_at()
    {
        return $this->wrappedObject->modified_at();
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
        if ($this->categoryReadRepository->existsBySlug($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('blog', 'This slug "{value}" is already exists.', ['value' => $this->$attribute]));
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
            $this->parent_id = $this->wrappedObject->parent_id;
            $this->status = $this->wrappedObject->status;
            $this->title = $this->wrappedObject->title;
            $this->slug = $this->wrappedObject->slug;
            $this->description = $this->wrappedObject->description;
            $this->depth = $this->wrappedObject->depth;
            $this->meta_title = $this->wrappedObject->meta_title;
            $this->meta_keywords = $this->wrappedObject->meta_keywords;
            $this->meta_description = $this->wrappedObject->meta_description;
            $this->language = $this->wrappedObject->language;
            $this->lft = $this->wrappedObject->lft;
            $this->rgt = $this->wrappedObject->rgt;

        }

        if ($this->isNewRecord) {
            $this->status = $this->viewHelper->getDefaultStatus();
            $this->parent_id = Settings::categoryRootId();
            $this->language = Settings::defaultLanguage();
        }

        return $this;
    }

    protected function reverseTransform()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        return $this->getAttributes();
    }
}
