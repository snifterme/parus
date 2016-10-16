<?php

namespace rokorolov\parus\user\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Controller;

/**
 * This is the AuthorizationController.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AuthorizationController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = '@rokorolov/parus/user/views/layouts/authorization';
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = Yii::createObject('rokorolov\parus\user\models\form\LoginForm');
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', compact('model'));
        }
    }

    /**
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
