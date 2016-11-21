<?php

namespace rokorolov\parus\blog\models\form;

use rokorolov\parus\blog\helpers\PostViewHelper;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\repositories\PostReadRepository;
use rokorolov\parus\blog\Module;
use rokorolov\helpers\Html;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Inflector;

/**
 * PostForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostForm extends Model
{
    public $id;
    public $category_id;
    public $status;
    public $title;
    public $slug;
    public $introtext;
    public $fulltext;
    public $language;
    public $view;
    public $version;
    public $reference;
    public $published_at;
    public $publish_up;
    public $publish_down;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $imageFile;

    public $isNewRecord = true;

    private $viewHelper;
    private $postReadRepository;
    private $wrappedObject;

    public function __construct(
        PostViewHelper $viewHelper,
        PostReadRepository $postReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->postReadRepository = $postReadRepository;

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

            ['category_id', 'required'],
            ['category_id', 'in', 'range' => array_keys($this->getCategoryOptions())],

            ['status', 'required'],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],
            ['status', 'default', 'value' => $this->viewHelper->getDefaultStatus()],

            [['title', 'slug', 'reference'], 'string', 'max' => 128],

            ['language', 'required'],
            ['language', 'in', 'range' => array_keys($this->getLanguageOptions())],

            ['slug', 'validateSlug'],

            [['meta_keywords', 'meta_description', 'view'], 'string', 'max' => 255],

            [['introtext', 'fulltext'], 'safe'],

            [['publish_up', 'publish_down'], 'default', 'value' => null],

            ['published_at', 'default', 'value' => Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s')],
            
            ['imageFile', 'file', 'skipOnEmpty' => true,
                'extensions' => implode(',', Settings::postIntroImageAllowedExtensions()),
                'mimeTypes' => implode(',', Settings::postIntroImageAllowedMimeTypes()),
                'minSize' => Settings::postIntroImageMinSize(),
                'maxSize' => Settings::postIntroImageMaxSize()
            ],
            
            ['imageFile', 'image', 'skipOnEmpty' => true,
                'minWidth' => Settings::postIntroImageMinWidth(),
                'maxWidth' => Settings::postIntroImageMaxWidth(),
                'minHeight' => Settings::postIntroImageMinHeight(),
                'maxHeight' => Settings::postIntroImageMaxHeight()
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
            'category_id',
            'status',
            'title',
            'slug',
            'introtext',
            'fulltext',
            'language',
            'view',
            'reference',
            'published_at',
            'publish_up',
            'publish_down',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'imageFile'
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

    public function getCategoryOptions()
    {
        return $this->viewHelper->getCategoryOptions();
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
        if ($this->postReadRepository->existsBySlug($this->$attribute, $this->id)) {
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
            $this->category_id = $this->wrappedObject->category_id;
            $this->status = $this->wrappedObject->status;
            $this->title = $this->wrappedObject->title;
            $this->slug = $this->wrappedObject->slug;
            $this->introtext = $this->wrappedObject->introtext;
            $this->fulltext = $this->wrappedObject->fulltext;
            $this->language = $this->wrappedObject->language;
            $this->view = $this->wrappedObject->view;
            $this->version = $this->wrappedObject->version;
            $this->reference = $this->wrappedObject->reference;
            $this->published_at = $this->wrappedObject->published_at;
            $this->publish_up = $this->wrappedObject->publish_up;
            $this->publish_down = $this->wrappedObject->publish_down;
            $this->meta_title = $this->wrappedObject->meta_title;
            $this->meta_keywords = $this->wrappedObject->meta_keywords;
            $this->meta_description = $this->wrappedObject->meta_description;
        }

        if ($this->isNewRecord) {
            $this->status = $this->viewHelper->getDefaultStatus();
            $this->category_id = Settings::categoryDefaultId();
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
