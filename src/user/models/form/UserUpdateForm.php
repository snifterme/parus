<?php

namespace rokorolov\parus\user\models\form;

use rokorolov\parus\user\Module;
use rokorolov\parus\user\helpers\ViewHelper;
use rokorolov\parus\user\repositories\UserRepository;
use Yii;
use yii\base\Model;

/**
 * UserUpdateForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserUpdateForm extends Model
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 10;
    
    public $id;
    public $profile;
    public $username;
    public $email;
    public $status;
    public $current_password;
    public $new_password;
    public $repeat_password;
    public $role;
    
    private $password_hash;
    
    private $userRepository;
    private $wrappedObject;
    private $viewHelper;
    
    public function __construct(
        UserRepository $userRepository,
        ViewHelper $viewHelper,
        $config = []
    ) {
        $this->userRepository = $userRepository;
        $this->viewHelper = $viewHelper;
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'new_password', 'repeat_password', 'current_password'], 'filter', 'filter' => 'trim'],

            ['username', 'required'],
            ['username', 'validateUsername'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'validateEmail'],

            ['role', 'in', 'range' => $this->viewHelper->getAllRoles()],
            
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_BLOCKED]],

            ['current_password', 'required', 'when' => function ($model){
                    return !empty($model->new_password);
                }, 'whenClient' => "function (attribute, value) {
                        return !!$('#userupdateform-new_password').val().length;
                }", 'skipOnEmpty' => false
            ],
            ['current_password', 'validateCurrentPassword'],

            ['new_password', 'string', 'min' => 6, 'max' => 30],

            ['repeat_password', 'compare', 'compareAttribute' => 'new_password', 'skipOnEmpty' => false],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'username',
            'email',
            'role',
            'status',
            'new_password'
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
        return $this->wrappedObject->updated_at_medium_with_relative(true);
    }
    
    public function getCreated_at()
    {
        return $this->wrappedObject->created_at();
    }
    
    public function getupdated_at()
    {
        return $this->wrappedObject->updated_at();
    }

    public function getLastLoginOnMediumWithRelative()
    {
        return $this->wrappedObject->last_login_on_medium_with_relative();
    }
    

    public function getLast_login_on()
    {
        if (null === $lastLoginOn = $this->wrappedObject->last_login_on()) {
            return Module::t('user', 'Never login');
        }
        
        return $lastLoginOn;
    }

    /**
     * Get last login IP.
     *
     * @return string
     */
    public function getLast_login_ip()
    {
        return $this->wrappedObject->last_login_ip;
    }
    
    /**
     * 
     * @param type $attribute
     */
    public function validateUsername($attribute)
    {
        if ($this->userRepository->existsByUsername($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('user', 'This username "{value}" has already been taken.', ['value' => $this->$attribute]));
        }
    }

    /**
     * 
     * @param type $attribute
     */
    public function validateEmail($attribute)
    {
        if ($this->userRepository->existsByEmail($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('user', 'This email "{value}" has already been taken.', ['value' => $this->$attribute]));
        }
    }
    
    /**
     * Validate the password.
     */
    public function validateCurrentPassword()
    {
        if (!Yii::$app->security->validatePassword($this->current_password, $this->password_hash)) {
            $this->addError('current_password', Module::t('user', 'Not valid current password.'));
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
    public function setData($data)
    {
        return $this->transform($data);
    }
    
    /**
     * 
     * @param type $data
     * @return \rokorolov\parus\user\models\form\UserForm
     */
    protected function transform($data)
    {
        $this->wrappedObject = $data;
        $this->id = $this->wrappedObject->id;
        $this->email = $this->wrappedObject->email;
        $this->role = $this->wrappedObject->role;
        $this->username = $this->wrappedObject->username;
        $this->status = $this->wrappedObject->status;
        $this->password_hash = $this->wrappedObject->password_hash;
        $this->profile = Yii::createObject('rokorolov\parus\user\models\form\ProfileForm', [$this->viewHelper]);
        $this->profile->language = $this->wrappedObject->profile->language;

        return $this;
    }
    
    /**
     * 
     * @return type
     */
    protected function reverseTransform()
    {
        return array_merge($this->getAttributes(), $this->profile->getAttributes(), ['model' => $this->wrappedObject]);
    }
}


