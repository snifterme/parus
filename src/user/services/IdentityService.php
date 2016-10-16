<?php

namespace rokorolov\parus\user\services;

use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use rokorolov\parus\user\helpers\Settings;
use rokorolov\parus\user\dto\UserDto;
use Yii;
use yii\caching\TagDependency;
use yii\web\IdentityInterface;

/**
 * IdentityService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class IdentityService implements IdentityInterface
{
    public $id;
    public $username;
    public $email;
    public $auth_key;
    public $password_hash;
    public $password_reset_token;
    public $status;
    public $name;
    public $surname;
    public $language;
    public $role;
    public $avatar_url;
    public $last_login_on;
    public $last_login_ip;
    public $created_at;
    public $updated_at;
    
    private static $userReadRepository;
    
    public function __construct(UserDto $user)
    {
        $this->id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->auth_key = $user->auth_key;
        $this->password_hash = $user->password_hash;
        $this->password_reset_token = $user->password_reset_token;
        $this->status = $user->status;
        $this->name = $user->profile->name;
        $this->surname = $user->profile->surname;
        $this->language = $user->profile->language;
        $this->avatar_url = $user->profile->avatar_url;
        $this->last_login_on = $user->profile->last_login_on;
        $this->last_login_ip = $user->profile->last_login_ip;
        $this->created_at = $user->created_at;
        $this->updated_at = $user->updated_at;
        $this->role = $user->role;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $cacheKey = static::class . $id;
        if (false === $identity = Yii::$app->cache->get($cacheKey)) {
            $identity = new self(self::getUserReadRepository()->skipPresenter()->findById($id, ['profile']));
            Yii::$app->cache->set(
                $cacheKey,
                $identity,
                86400,
                new TagDependency(
                    [
                        'tags' => [
                            TagDependencyNamingHelper::getObjectTag(Settings::userDependencyTagName(), $id),
                            TagDependencyNamingHelper::getObjectTag(Settings::profileDependencyTagName(), $id),
                        ]
                    ]
                )
            );
        }
        
        return $identity;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * 
     * @return type
     */
    private static function getUserReadRepository()
    {
        if (self::$userReadRepository === null) {
            self::$userReadRepository = Yii::createObject('rokorolov\parus\user\repositories\UserReadRepository');
        }
        return self::$userReadRepository;
    }
}
