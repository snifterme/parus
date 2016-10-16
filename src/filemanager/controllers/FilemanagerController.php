<?php

namespace rokorolov\parus\filemanager\controllers;

use rokorolov\parus\filemanager\services\AccessControlService;
use rokorolov\parus\filemanager\Module;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * FilemanagerController implements the CRUD actions for Album model.
 */
class FilemanagerController extends Controller
{
    private $accessControl;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        $id,
        $module,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->accessControl = $accessControl;
        
        parent::__construct($id, $module, $config);
    }
    
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
                        'allow' => true,
                        'actions' => ['index'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canManageFilemanager();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Album models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
