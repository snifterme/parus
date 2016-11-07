<?php

namespace rokorolov\parus\gallery\models\form;

use rokorolov\parus\gallery\helpers\AlbumViewHelper;
use rokorolov\parus\gallery\Module;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\gallery\repositories\AlbumReadRepository;
use rokorolov\parus\admin\traits\TranslatableFormTrait;
use rokorolov\helpers\Html;
use Yii;
use yii\web\UploadedFile;
use yii\base\Model;

/**
 * AlbumForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumForm extends Model
{
    use TranslatableFormTrait;

    public $id;
    public $status;
    public $album_alias;
    public $created_at;
    public $modified_at;
    public $imageFile;
    public $translations = [];

    public $isNewRecord = true;

    private $viewHelper;
    private $albumReadRepository;
    private $wrappedObject;

    public function __construct(
        AlbumViewHelper $viewHelper,
        AlbumReadRepository $albumReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->albumReadRepository = $albumReadRepository;
        $this->translatableConfig = Settings::translatableConfig();

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['album_alias', 'filter', 'filter' => 'trim'],
            
            ['status', 'required'],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],
            ['status', 'default', 'value' => $this->viewHelper->getDefaultStatus()],
            
            ['album_alias', 'required'],
            ['album_alias', 'string', 'max' => 128],
            ['album_alias', 'validateAlbumAlias'],
            
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => implode(',', Settings::albumIntroImageAllowedExtensions())],
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
            'album_alias',
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
    
    public function getCurrentStatus()
    {
        if ($this->isNewRecord) {
            return Html::a(Html::icon('circle text-info') . ' ' . Module::t('gallery', 'Not set'), ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
        }
        return Html::a(Html::icon('circle text-' . $this->viewHelper->getStatusHtmlType($this->status)) . ' <strong>' . $this->viewHelper->getStatusName($this->status) . '</strong>', ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
    }

    public function getImageOriginal()
    {
        if (!$this->isNewRecord && null !== $image = $this->wrappedObject->image_original()) {
            return $image;
        }
        return null;
    }
    
    public function getSavedOn()
    {
        if ($this->isNewRecord) {
            return Module::t('gallery', 'album has not been saved yet.');
        }
        
        return $this->wrappedObject->modified_at_medium_with_relative(true);
    }
    
    public function getCreated_at()
    {
        return $this->wrappedObject->created_at();
    }
    
    public function getModified_at()
    {
        return $this->wrappedObject->modified_at();
    }

    public function validateAlbumAlias($attribute)
    {
        if ($this->albumReadRepository->existsByAlbumAlias($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('gallery', 'Album alias "{value}" is already exists.', ['value' => $this->$attribute]));
        }
    }
    
    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && Model::loadMultiple($this->getTranslationVariations(), $data);
    }

    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors) && Model::validateMultiple($this->translationVariations);
    }

    /**
     *
     * @return type
     */
    public function getData()
    {
        return $this->reverseTransform();
    }

    /**
     *
     * @param type $data
     * @return type
     */
    public function setData($data = null)
    {
        return $this->transform($data);
    }

    /**
     *
     * @param type $data
     * @return
     */
    protected function transform($data = null)
    {
        if ($data !== null) {
            $this->isNewRecord = false;
            $this->wrappedObject = $data;
            $this->id = $this->wrappedObject->id;
            $this->status = $this->wrappedObject->status;
            $this->album_alias = $this->wrappedObject->album_alias;
            $this->created_at = $this->wrappedObject->created_at;
            $this->modified_at = $this->wrappedObject->modified_at;

            foreach($this->wrappedObject->translations as $key => $translation) {
                $formTranslation = $this->getTranslatableModel();
                $formTranslation->album_id = $translation->album_id;
                $formTranslation->language = $translation->language;
                $formTranslation->name = $translation->name;
                $formTranslation->description = $translation->description;
                $this->translations[$translation->{$this->translatableConfig['translationLanguageAttribute']}] = $formTranslation;
            }
        }

        if ($this->isNewRecord) {
            $this->status = $this->viewHelper->getDefaultStatus();
        }

        return $this;
    }

    protected function reverseTransform()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        return array_merge($this->getAttributes(), ['translations' => $this->reverseTranslations()]);
    }

    protected function getTranslatableModel()
    {
        return Yii::createObject('rokorolov\parus\gallery\models\form\AlbumLangForm', [$this->viewHelper]);
    }
}
