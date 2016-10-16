<?php

namespace rokorolov\parus\user\models\form;

use rokorolov\parus\user\Module;
use rokorolov\parus\user\helpers\ViewHelper;
use rokorolov\parus\user\repositories\UserRepository;
use rokorolov\parus\user\services\AccessControlService;
use Yii;
use yii\base\Model;

/**
 * UserCreateForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserCreateForm extends Model
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 10;
    
    public $profile;
    public $username;
    public $email;
    public $status;
    public $password;
    public $repeat_password;
    public $role;
    
    private $userRepository;
    private $viewHelper;
    private $accessControl;
    
    public function __construct(
        UserRepository $userRepository,
        ViewHelper $viewHelper,
        AccessControlService $accessControl,
        $config = []
    ) {
        $this->userRepository = $userRepository;
        $this->viewHelper = $viewHelper;
        $this->accessControl = $accessControl;
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'repeat_password'], 'filter', 'filter' => 'trim'],
            
            ['username', 'required'],
            ['username', 'validateUsername'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'validateEmail'],
            
            ['role', 'required'],
            ['role', 'in', 'range' => array_keys($this->getRoleOptions())],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED]],
            
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 30],
            
            ['repeat_password', 'required'],
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],     
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'username',
            'email',
            'role',
            'status',
            'password'
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
    public function getRoleOptions()
    {
        return $this->viewHelper->getRoleOptions();
    }
    
    public function getSavedOn()
    {
        return Module::t('user', 'user has not been saved yet.');
    }
    
    /**
     * 
     * @param type $attribute
     */
    public function validateUsername($attribute)
    {
        if ($this->userRepository->existsByUsername($this->$attribute)) {
            $this->addError($attribute,  Module::t('user', 'This username "{value}" has already been taken.', ['value' => $this->$attribute]));
        }
    }

    /**
     * 
     * @param type $attribute
     */
    public function validateEmail($attribute)
    {
        if ($this->userRepository->existsByEmail($this->$attribute)) {
            $this->addError($attribute,  Module::t('user', 'This email "{value}" has already been taken.', ['value' => $this->$attribute]));
        }
    }
    
    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        return parent::load($data, $formName) && $this->profile->load($data);
    }
    
    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors) && $this->profile->validate();
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
    public function setData()
    {
        return $this->transform();
    }
    
    /**
     * 
     * @param type $data
     * @return \rokorolov\parus\user\models\UserForm
     */
    protected function transform()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->profile = Yii::createObject('rokorolov\parus\user\models\form\ProfileForm', [$this->viewHelper]);
        
        return $this;
    }
    
    /**
     * 
     * @return type
     */
    protected function reverseTransform()
    {
        return array_merge($this->getAttributes(), $this->profile->getAttributes());
    }
}


