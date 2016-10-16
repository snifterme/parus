<?php

namespace rokorolov\parus\user\commands;

use rokorolov\parus\user\repositories\UserRepository;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreateUserHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateUserHandler
{
    use PurifierTrait;
    
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(CreateUserCommand $command)
    {
        $this->guardUsernameIsUnique($command->getUsername());
        $this->guardEmailIsUnique($command->getEmail());

        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');

        $user = $this->userRepository->makeUserCreateModel();
        $user->email = $command->getEmail();
        $user->username = $this->textPurify($command->getUsername());
        $user->status = $command->getStatus();
        $user->role = $command->getRole();
        $user->setPassword($command->getPassword());
        $user->setAuthKey();
        $user->created_at = $datetime;
        $user->updated_at = $datetime;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->userRepository->add($user);
            $user->profile->user_id = $user->id;
            $user->profile->language = $command->getLanguage();
            $user->profile->name = $command->getName();
            $user->profile->surname = $command->getSurname();
            $this->userRepository->addProfile($user->profile);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $command->model = $user;
    }

    private function guardUsernameIsUnique($username)
    {
        if ($this->userRepository->existsByUsername($username)) {
            throw new LogicException('Username already exists');
        }
    }

    private function guardEmailIsUnique($email)
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new LogicException('Email already exists');
        }
    }
}
