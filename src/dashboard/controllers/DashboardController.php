<?php

namespace rokorolov\parus\dashboard\controllers;

use rokorolov\parus\dashboard\services\AccessControlService;
use rokorolov\parus\dashboard\services\DashboardService;
use rokorolov\parus\dashboard\helpers\Settings;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * This is the rokorolov\parus\dashboard\controllers\DashboardController.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DashboardController extends Controller
{
    private $service;
    private $accessControl;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        $id,
        $module,
        DashboardService $service,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->service = $service;
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
                            return $this->accessControl->canManageDashboard();
                        }
                    ],
                ],
            ]
        ];
    }
    
    /**
     * Displays a dasboard.
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'recentlyAddedPosts' => $this->service->getLastAddedPost(Settings::lastAddedPostLimit()),
            'popularPosts' => $this->service->getPopularPost(Settings::popularPostLimit()),
            'statusManager' => $this->service->getStatusManager(),
            'accessControl' => $this->accessControl
        ]);
    }
}