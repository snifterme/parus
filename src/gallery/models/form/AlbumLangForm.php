<?php

namespace rokorolov\parus\gallery\models\form;

use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\gallery\Module;
use yii\base\Model;

/**
 * AlbumLangForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumLangForm extends Model
{
    public $album_id;
    public $language;
    public $name;
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
            [['name', 'description'], 'trim'],
            ['name', 'required', 'when' => function ($model) {
                    return $model->language === $this->defaultLanguage;
                }, 'whenClient' => "function (attribute, value) {
                    return attribute.name.indexOf(['$this->defaultLanguage']) == 1;
                }", 'message' => '{attribute} ' . Module::t('gallery', 'is required at least in {langTitle} {language}.', [
                    'langTitle' => Settings::defaultLanguageTitle(),
                    'language' => Module::t('gallery', 'language')
                ])
            ],
            ['name', 'string', 'max' => 128],
            ['description', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'album_id',
            'language',
            'name',
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


