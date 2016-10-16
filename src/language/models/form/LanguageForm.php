<?php

namespace rokorolov\parus\language\models\form;

use rokorolov\parus\language\Module;
use rokorolov\parus\language\helpers\ViewHelper;
use rokorolov\parus\language\repositories\LanguageReadRepository;
use yii\base\Model;

/**
 * LanguageForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguageForm extends Model
{
    public $id;
    public $title;
    public $status;
    public $order;
    public $lang_code;
    public $image;
    public $date_format;
    public $date_time_format;
    public $created_at;
    public $modified_at;

    public $isNewRecord = true;

    private $languageReadRepository;
    private $viewHelper;
    private $wrappedObject;

    public function __construct(
        ViewHelper $viewHelper,
        LanguageReadRepository $languageReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->languageReadRepository = $languageReadRepository;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'lang_code', 'image', 'order', 'date_format', 'date_time_format'], 'trim'],

            ['title', 'required'],
            ['title', 'string', 'max' => 128],

            ['lang_code', 'required'],
            ['lang_code', 'validateLangCode'],
            ['lang_code', 'string', 'max' => 7],

            ['status', 'default', 'value' => $this->viewHelper->getDefaultStatus()],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],

            ['order', 'integer'],
            ['order', 'default', 'value' => '0'],

            ['image', 'string', 'max' => 64],

            ['date_format', 'string', 'max' => 32],
            ['date_format', 'default', 'value' => 'Y-m-d'],

            ['date_time_format', 'string', 'max' => 32],
            ['date_time_format', 'default', 'value' => 'Y-m-d H:i:s'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'title',
            'status',
            'order',
            'lang_code',
            'image',
            'date_format',
            'date_time_format'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->viewHelper->getAttributeLabels();
    }

    /**
     *
     * @return type
     */
    public function getStatusOptions()
    {
        return $this->viewHelper->getStatusOptions();
    }
    
    /**
     * 
     * @param type $attribute
     */
    public function validateLangCode($attribute)
    {
        if ($this->languageReadRepository->existsByLangCode($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('language', 'This language code "{value}" is already exists.', ['value' => $this->$attribute]));
        }
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
            $this->title = $this->wrappedObject->title;
            $this->status = $this->wrappedObject->status;
            $this->order = $this->wrappedObject->order;
            $this->lang_code = $this->wrappedObject->lang_code;
            $this->image = $this->wrappedObject->image;
            $this->date_format = $this->wrappedObject->date_format;
            $this->date_time_format = $this->wrappedObject->date_time_format;
            $this->created_at = $this->wrappedObject->created_at;
            $this->modified_at = $this->wrappedObject->modified_at;
        }

        if ($this->isNewRecord) {
            $this->status = $this->viewHelper->getDefaultStatus();
        }

        return $this;
    }

    protected function reverseTransform()
    {
        return $this->getAttributes();
    }
}
