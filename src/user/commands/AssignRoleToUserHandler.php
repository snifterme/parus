<?php

namespace rokorolov\parus\user\commands;

use rokorolov\parus\user\repositories\UserRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * AssignRoleToUserHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AssignRoleToUserHandler
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function handle(AssignRoleToUserCommand $command)
    {
        if (null === $user = $this->userRepository->findById($command->getUserId())) {
            throw new LogicException('User does not exist.');
        }
        
        $user->role = $command->getRole();
        $this->userRepository->update($user);
    }
}