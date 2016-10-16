<?php

namespace rokorolov\parus\user\models\form;

use rokorolov\parus\user\Module;
use rokorolov\parus\user\models\User;
use rokorolov\parus\user\repositories\UserReadRepository;
use rokorolov\parus\user\helpers\SecurityHelper;
use yii\web\IdentityInterface;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $user = false;
    private $userReadRepository;
    private $securityHelper;

    public function __construct(UserReadRepository $userReadRepository, SecurityHelper $securityHelper, $config = array())
    {
        $this->userReadRepository = $userReadRepository;
        $this->securityHelper = $securityHelper;
        
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('user/authorization', 'Username'),
            'password' => Module::t('user/authorization', 'Password'),
            'rememberMe' => Module::t('user/authorization', 'Remember Me'),
        ];
    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$this->securityHelper->validatePassword($this->password, $user->password_hash)) {
                $this->addError($attribute, Module::t('user/authorization', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $identity = Yii::createObject(IdentityInterface::class, [$this->getUser()]);
            return Yii::$app->user->login($identity, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = $this->userReadRepository->skipPresenter()->findByUsernameOrEmail($this->username, ['profile']);
        }

        return $this->user;
    }
}
