<?php

namespace rokorolov\parus\admin\controllers;

use rokorolov\parus\admin\Module;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

 /**
 * This is the AdminController.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AdminController extends Controller
{
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
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'error', 'clear-cache'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Index action.
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Clear cache action.
     * 
     * @return mixed
     */
    public function actionClearCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', Module::t('admin', 'Cache has been successfully cleared!'));
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}
