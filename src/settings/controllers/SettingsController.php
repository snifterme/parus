<?php

namespace rokorolov\parus\settings\controllers;

use rokorolov\parus\settings\repositories\SettingsReadRepository;
use rokorolov\parus\settings\commands\UpdateSettingCommand;
use rokorolov\parus\settings\services\AccessControlService;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\settings\Module;
use rokorolov\parus\settings\helpers\Settings;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\base\Model;

/**
 * AdminController implements the CRUD actions for Settings model.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsController extends Controller
{
    private $commandBus;
    private $accessControl;
    private $settingsReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        SettingsReadRepository $settingsReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->settingsReadRepository = $settingsReadRepository;
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
                            return $this->accessControl->canManageSettings();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param string $id
     * @return mixed
     */
    public function actionIndex()
    {
        $settings = $this->settingsReadRepository->findAllWithTranslation(Settings::panelLanguage());

        $models = [];
        foreach($settings as $setting) {
            $models[$setting->id] = Yii::createObject('rokorolov\parus\settings\models\form\SettingsUpdateForm')->setData($setting);
        }
        
        if (Yii::$app->request->post() && !$this->accessControl->canUpdateSettings()) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        
        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            foreach($models as $model) {
                if ($model->isValueChanged) {
                    $data = $model->getData();
                    $this->commandBus->execute(new UpdateSettingCommand(
                        $data['param'],
                        $data['value']
                    ));
                }
            }
            Yii::$app->session->setFlash('success', Module::t('settings', 'Settings is successfuly updated!'));
            
            return $this->refresh();
        }
        
        return $this->render('update', [
            'models' => $models,
            'accessControl' => $this->accessControl
        ]);
    }
}
