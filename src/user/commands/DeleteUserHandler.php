<?php

namespace rokorolov\parus\user\commands;

use rokorolov\parus\user\repositories\UserRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteUserHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteUserHandler
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function handle(DeleteUserCommand $command)
    {
        if (null === $user = $this->userRepository->findById($command->getId())) {
            throw new LogicException('User does not exist.');
        }
        
        $this->userRepository->remove($user);
    }
}
