<?php

namespace rokorolov\parus\user\commands;

use rokorolov\parus\user\repositories\UserRepository;
use Yii;

/**
 * AfterUserLoginHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AfterUserLoginHandler
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function handle(AfterUserLoginCommand $command)
    {
        $userId = $command->getUserId();
        $profile = $this->userRepository->findById($userId)->profile;
        
        $profile->last_login_ip = Yii::$app->getRequest()->getUserIP();
        $profile->last_login_on = Yii::$app->formatter->asDateTime('now', 'php:Y-m-d H:i:s');
        
        Yii::$app->session->set('user.lastLoginOn', $profile->last_login_on);
        Yii::$app->session->set('user.lastLoginIP', $profile->last_login_ip);
        
        $this->userRepository->updateProfile($profile);
    }
}