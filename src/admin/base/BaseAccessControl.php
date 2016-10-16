<?php

namespace rokorolov\parus\admin\base;

use Yii;

/**
 * BaseAccessControl
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class BaseAccessControl
{
    private $authManager;
    
    public function assign($role, $user)
    {
        $auth = $this->getAuthManager();
        $authorRole = $auth->getRole($role);
        $auth->assign($authorRole, $user);
    }
    
    public function isSuperAdmin($userId = null)
    {
        if (!Yii::$container->has('rokorolov\parus\admin\contracts\RbacServiceInterface')) {
            Yii::$container->set('rokorolov\parus\admin\contracts\RbacServiceInterface', 'rokorolov\parus\admin\rbac\RbacService');
        }
        
        return isset(Yii::$app->authManager->getRolesByUser($userId)[Yii::createObject('rokorolov\parus\admin\contracts\RbacServiceInterface')->getRoleSuperAdmin()]);
    }
    
    private function getAuthManager()
    {
        if ($this->authManager === null) {
            $this->authManager = Yii::$app->authManager;
        }
        return $this->authManager;
    }
}
