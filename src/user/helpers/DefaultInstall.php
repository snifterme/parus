<?php

namespace rokorolov\parus\user\helpers;

use rokorolov\parus\user\contracts\DefaultInstallInterface;
use Yii;

/**
 * DefaultInstall
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DefaultInstall implements DefaultInstallInterface
{
    public $installDefaults = true;
    
    protected $systemId = 1;

    public function shouldInstallDefaults()
    {
        return $this->installDefaults;
    }
    
    public function getSystemId()
    {
        return $this->systemId;
    }
    
    public function getUserParams()
    {
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        
        if (!Yii::$container->has('rokorolov\parus\admin\contracts\RbacServiceInterface')) {
            Yii::$container->set('rokorolov\parus\admin\contracts\RbacServiceInterface', 'rokorolov\parus\admin\rbac\RbacService');
        }
        
        return [
            'id' => $this->systemId,
            'username' => 'admin',
            'role' => Yii::createObject('rokorolov\parus\admin\contracts\RbacServiceInterface')->getRoleSuperAdmin(),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('password'),
            'email' => 'admin@admin.com',
            'status' => '10',
            'created_at' => $datetime,
            'updated_at' => $datetime
        ];
    }
    
    public function getUserProfileParams()
    {
        return [
            'user_id' => $this->systemId,
            'name' => 'Administrator',
            'language' => null,
            'surname' => '',
            'avatar_url' => '',
            'last_login_ip' => ''
        ];
    }
}
