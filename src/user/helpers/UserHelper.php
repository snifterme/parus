<?php

namespace rokorolov\parus\user\helpers;

use rokorolov\parus\user\helpers\Settings;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use Yii;
use yii\caching\TagDependency;

/**
 * UserHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserHelper
{
    private $id;
    
    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->id = $id;
        } else {
            $this->id = Yii::$app->user->identity->id;
        }
    }
    
    /**
     * Get email.
     * 
     * @return array
     */
    public function getEmail()
    {
        return $this->getUser()['email'];
    }
    
    /**
     * Get email.
     * 
     * @return array
     */
    public function getStatus()
    {
        return $this->getUser()['status'];
    }
    
    /**
     * Get email.
     * 
     * @return array
     */
    public function getUsername()
    {
        return $this->getUser()['username'];
    }
    
    /**
     * Get languages.
     * 
     * @return array
     */
    protected function getUser()
    {
        $cacheKey = static::class . $this->id;
        if (false === $user = Yii::$app->cache->get($cacheKey)) {
            $userRepository = Yii::createObject('rokorolov\parus\user\repositories\UserReadRepository');
            if (null === $user = $userRepository->getUser($this->id)) {
                throw new NotFoundHttpException;
            }
            Yii::$app->cache->set(
                $cacheKey,
                $user,
                86400,
                new TagDependency(
                    [
                        'tags' => [
                            TagDependencyNamingHelper::getObjectTag(Settings::userDependencyTagName(), $this->id),
                            TagDependencyNamingHelper::getObjectTag(Settings::profileDependencyTagName(), $this->id),
                        ]
                    ]
                )
            );
        }
        
        return $user;
    }
}
