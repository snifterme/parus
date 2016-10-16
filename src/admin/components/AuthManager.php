<?php

namespace rokorolov\parus\admin\components;

use rokorolov\parus\user\commands\AssignRoleToUserCommand;
use Yii;
use yii\rbac\PhpManager;
use yii\rbac\Assignment;

/**
 * AuthManager
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AuthManager extends PhpManager
{
    private $userReadRepository;
    
    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if ($user = $this->getUser($userId)) {
            $assignment = new Assignment();
            $assignment->userId = (int)$userId;
            $assignment->roleName = $user->role;
            return [$assignment->roleName => $assignment];
        }
    }

    /**
     * @inheritdoc
     */
    public function getAssignment($roleName, $userId)
    {
        if ($user = $this->getUser($userId)) {
            if ($user->role === $roleName) {
                $assignment = new Assignment();
                $assignment->userId = $userId;
                $assignment->roleName = $user->role;
                return $assignment;                
            }
        }
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function assign($role, $userId)
    {
        $this->setRole($userId, $role->name);
    }
    
    /**
     * @inheritdoc
     */
    public function revokeAll($userId)
    {
        $this->setRole($userId, null);
    }
    
    private function getUser($userId)
    {
        if (!Yii::$app->user->isGuest && (int)Yii::$app->user->id === (int)$userId) {
            return Yii::$app->user->identity;
        } else {
            return $this->getUserReadRepository()->findById($userId);
        }
    }
    
    private function setRole($userId, $roleName)
    {
        $commandBus = Yii::createObject('rokorolov\parus\admin\contracts\CommandBusInterface');
        $commandBus->execute(new AssignRoleToUserCommand(
            $roleName,
            $userId
        ));
    }
    
    private function getUserReadRepository()
    {
        if ($this->userReadRepository === null) {
            $this->userReadRepository = Yii::createObject('rokorolov\parus\user\repositories\UserReadRepository');
        }
        return $this->userReadRepository;
    }
}
