<?php

namespace rokorolov\parus\gallery\models\form;

use rokorolov\parus\gallery\helpers\Settings;
use yii\base\Model;

/**
 * PhotoLangForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoLangForm extends Model
{
    public $photo_id;
    public $language;
    public $caption;
    public $description;
    
    private $viewHelper;
    private $defaultLanguage;
    
    public function __construct(
        $viewHelper,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->defaultLanguage = Settings::defaultLanguage();
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['caption', 'description'], 'trim'],
            ['caption', 'string', 'max' => 128],
            ['description', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'photo_id',
            'language',
            'caption',
            'description'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->viewHelper->getAttributeLabels();
    }
}


