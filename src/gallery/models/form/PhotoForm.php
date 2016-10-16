<?php

namespace rokorolov\parus\gallery\models\form;

use rokorolov\parus\gallery\helpers\PhotoViewHelper;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\admin\traits\TranslatableFormTrait;
use Yii;
use yii\base\Model;

/**
 * PhotoForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoForm extends Model
{
    use TranslatableFormTrait;

    public $id;
    public $status;
    public $order;
    public $album_id;
    public $photo_name;
    public $photo_size;
    public $photo_extension;
    public $photo_mime;
    public $photo_path;
    public $translations = [];

    public $isNewRecord = true;

    private $viewHelper;
    private $wrappedObject;

    public function __construct(
        PhotoViewHelper $viewHelper,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->translatableConfig = Settings::translatableConfig();

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'required'],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],
            ['status', 'default', 'value' => $this->viewHelper->getDefaultStatus()],
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
            'order',
            'album_id'
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

    public function getStatusActions()
    {
        return $this->viewHelper->getStatusActions();
    }

    public function getImageThumb()
    {
        return $this->wrappedObject->image_thumb();
    }

    public function getImageOriginal()
    {
        return $this->wrappedObject->image_original();
    }
    
    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return isset($data['PhotoForm']) ? parent::load($data, $formName) && Model::loadMultiple($this->getTranslationVariations(), $data) : Model::loadMultiple($this->getTranslationVariations(), $data);
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
            $this->order = $this->wrappedObject->order;
            $this->album_id = $this->wrappedObject->album_id;
            $this->photo_name = $this->wrappedObject->photo_name;
            $this->photo_size = $this->wrappedObject->photo_size;
            $this->photo_extension = $this->wrappedObject->photo_extension;
            $this->photo_mime = $this->wrappedObject->photo_mime;
            $this->photo_path = $this->wrappedObject->photo_path;

            foreach($this->wrappedObject->translations as $key => $translation) {
                $formTranslation = $this->getTranslatableModel();
                $formTranslation->photo_id = $translation->photo_id;
                $formTranslation->language = $translation->language;
                $formTranslation->caption = $translation->caption;
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
        return array_merge($this->getAttributes(), ['translations' => $this->reverseTranslations()]);
    }


    protected function getTranslatableModel()
    {
        return Yii::createObject('rokorolov\parus\gallery\models\form\PhotoLangForm', [$this->viewHelper]);
    }
}
