<?php

namespace rokorolov\parus\user\console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * RbacController.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'init';
    
    /**
     * Init Rbac
     */
    public function actionInit()
    {
        if (!Yii::$container->has('rokorolov\parus\admin\contracts\RbacServiceInterface')) {
            Yii::$container->set('rokorolov\parus\admin\contracts\RbacServiceInterface', 'rokorolov\parus\admin\rbac\RbacService');
        }
        
        $this->stdout("\nStart RBAC Installation ...\n", Console::FG_YELLOW);
        Yii::createObject('rokorolov\parus\admin\rbac\RbacService')->init();
        $this->stdout("\nRBAC successfully installed.\n", Console::FG_GREEN);
    }
    
    /**
     * Remove Rbac
     */
    public function actionRemove()
    {
        Yii::$app->authManager->removeAll();
        $this->stdout("\nRemoved RBAC successfully.\n", Console::FG_GREEN);
    }
}
