<?php

namespace rokorolov\parus\language\controllers;

use rokorolov\parus\language\commands\UpdateLanguageCommand;
use rokorolov\parus\language\commands\CreateLanguageCommand;
use rokorolov\parus\language\commands\ChangeLanguageStatusCommand;
use rokorolov\parus\language\commands\DeleteLanguageCommand;
use rokorolov\parus\language\repositories\LanguageReadRepository;
use rokorolov\parus\language\services\AccessControlService;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\language\Module;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $commandBus;
    private $accessControl;
    private $languageReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        LanguageReadRepository $languageReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->languageReadRepository = $languageReadRepository;
        $this->accessControl = $accessControl;
        parent::__construct($id, $module, $config);
    }
    
    /**
     * 
     * @return type
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
                            return $this->accessControl->canManageLanguage();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewLanguage();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['newstatus'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdateLanguage();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeleteLanguage($this->findModel(Yii::$app->request->get('id')));
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'newstatus' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Language models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $id === null ? $model = null : $model = $this->findModel($id);
        $form = Yii::createObject('rokorolov\parus\language\models\form\LanguageForm')->setData($model);
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $data = $form->getData();
                if ($model) {
                    if (!$this->accessControl->canUpdateLanguage()) {
                        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                    }
                    $command = new UpdateLanguageCommand(
                        $data['id'],
                        $data['title'],
                        $data['status'],
                        $data['order'],
                        $data['lang_code'],
                        $data['image'],
                        $data['date_format'],
                        $data['date_time_format']
                    );
                    $this->commandBus->execute($command);
                    Yii::$app->session->setFlash('success', Module::t('language', 'Language is successfuly updated!'));
                } else {
                    if (!$this->accessControl->canCreateLanguage()) {
                        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                    }
                    $command = new CreateLanguageCommand(
                        $data['title'],
                        $data['status'],
                        $data['order'],
                        $data['lang_code'],
                        $data['image'],
                        $data['date_format'],
                        $data['date_time_format']
                    );
                    $this->commandBus->execute($command);
                    Yii::$app->session->setFlash('success', Module::t('language', 'Language is successfuly created!'));
                }
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('language', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['index']);
        }
        
        $searchModel = Yii::createObject('rokorolov\parus\language\models\search\LanguageSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Displays a single Language model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
            'accessControl' => $this->accessControl,
            'viewHelper' => Yii::createObject('rokorolov\parus\language\helpers\ViewHelper')
        ]);
    }

    /**
     * Deletes an existing Language model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new DeleteLanguageCommand($id));
            Yii::$app->session->setFlash('success', Module::t('language', 'Language is successfuly deleted!'));
            Yii::$app->cache->flush();
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('language', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect(['index']);
        }
    }
    
    /**
     * 
     * @param type $id
     * @param type $status
     */
    public function actionNewstatus($id, $status)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new ChangeLanguageStatusCommand($id, $status));
            Yii::$app->session->setFlash('success', Module::t('language', 'Changed status successfully!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('language', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
    /**
     * 
     * @param type $id
     * @param array $with
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findModel($id, array $with = [])
    {
        if (($model = $this->languageReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('language', 'The requested page does not exist.'));
        }
    }
}
