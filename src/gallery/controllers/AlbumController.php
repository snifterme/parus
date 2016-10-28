<?php

namespace rokorolov\parus\gallery\controllers;

use rokorolov\parus\gallery\repositories\AlbumReadRepository;
use rokorolov\parus\gallery\services\AccessControlService;
use rokorolov\parus\gallery\commands\CreateAlbumCommand;
use rokorolov\parus\gallery\commands\UpdateAlbumCommand;
use rokorolov\parus\gallery\commands\ChangeAlbumStatusCommand;
use rokorolov\parus\gallery\commands\DeleteAlbumCommand;
use rokorolov\parus\gallery\Module;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $commandBus;
    private $accessControl;
    private $albumReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        AlbumReadRepository $albumReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->albumReadRepository = $albumReadRepository;
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
                            return $this->accessControl->canManageAlbum();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewAlbum();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'newstatus'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdateAlbum();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreateAlbum();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeleteAlbum();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
        $searchModel = Yii::createObject('rokorolov\parus\gallery\models\search\AlbumSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Creates a new Album model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = Yii::createObject('rokorolov\parus\gallery\models\form\AlbumForm')->setData();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            
            try {
                $data = $form->getData();
                $command = new CreateAlbumCommand(
                    $data['status'],
                    $data['album_alias'],
                    $data['translations']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('gallery', 'Album is successfully created!'));
                return $this->redirect(['update', 'id' => $command->getId()]);
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('gallery', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                return $this->redirect(['create']);
            }
            
        }
        
        return $this->render('create', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Updates an existing Album model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, ['translations']);
        $form = Yii::createObject('rokorolov\parus\gallery\models\form\AlbumForm')->setData($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
          
            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdateAlbumCommand(
                    $data['id'],
                    $data['status'],
                    $data['album_alias'],
                    $data['translations']
                ));
                Yii::$app->session->setFlash('success', Module::t('gallery', 'Album is successfully updated!'));
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('gallery', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['update', 'id' => $id]);
        }
        
        return $this->render('update', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Deletes an existing Album model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new DeleteAlbumCommand($id));
            Yii::$app->session->setFlash('success', Module::t('gallery', 'Album is successfully deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('gallery', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
     * @return type
     */
    public function actionNewstatus($id, $status)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new ChangeAlbumStatusCommand($id, $status));
            Yii::$app->session->setFlash('success', Module::t('gallery', 'Changed status successfully!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('gallery', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
        if (($model = $this->albumReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('gallery', 'The requested page does not exist.'));
        }
    }
}
