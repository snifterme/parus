<?php

namespace rokorolov\parus\user\models\form;

use yii\base\Model;

/**
 * UserForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ProfileForm extends Model
{
    public $name;
    public $surname;
    public $language;

    private $viewHelper;
    
    public function __construct(
        $viewHelper,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname'], 'filter', 'filter' => 'trim'],
            
            ['name', 'string', 'min' => 2, 'max' => 50],
            
            ['surname', 'string', 'min' => 2, 'max' => 50],
            
            ['language', 'required'],
            ['language', 'in', 'range' => array_keys($this->getLanguageOptions())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'name',
            'surname',
            'language'
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
     * @return array
     */
    public function getLanguageOptions()
    {
        return $this->viewHelper->getLanguageOptions();
    }
}


