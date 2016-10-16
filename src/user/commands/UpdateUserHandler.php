<?php

namespace rokorolov\parus\user\commands;

use rokorolov\parus\user\repositories\UserRepository;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * UpdateUserHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateUserHandler
{
    use PurifierTrait;
    
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function handle(UpdateUserCommand $command)
    {
        $this->guardUsernameIsUnique($command->getUsername(), $command->getId());
        $this->guardEmailIsUnique($command->getEmail(), $command->getId());
        
        if (null === $user = $this->userRepository->findById($command->getId())) {
            throw new LogicException('User does not exist.');
        }
        
        $user->email = $command->getEmail();
        $user->username = $this->textPurify($command->getUsername());
        $user->role = $command->getRole();
        $user->status = $command->getStatus();
        $user->updated_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $user->profile->language = $command->getLanguage();
        $user->profile->name = $command->getName();
        $user->profile->surname = $command->getSurname();
        
        if (!empty($command->getPassword())) {
            $user->setPassword($command->getPassword());
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->userRepository->update($user);
            $this->userRepository->updateProfile($user->profile);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $user;
    }
    
    private function guardUsernameIsUnique($username, $exceptId)
    {
        if ($this->userRepository->existsByUsername($username, $exceptId)) {
            throw new LogicException('Username already exists');
        }
    }
    
    private function guardEmailIsUnique($email, $exceptId)
    {
        if ($this->userRepository->existsByEmail($email, $exceptId)) {
            throw new LogicException('Email already exists');
        }
    }
}
