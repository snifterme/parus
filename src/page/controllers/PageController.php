<?php

namespace rokorolov\parus\page\controllers;

use rokorolov\parus\page\repositories\PageReadRepository;
use rokorolov\parus\page\services\AccessControlService;
use rokorolov\parus\page\Module;
use rokorolov\parus\page\commands\CreatePageCommand;
use rokorolov\parus\page\commands\UpdatePageCommand;
use rokorolov\parus\page\commands\DeletePageCommand;
use rokorolov\parus\page\commands\ChangePageStatusCommand;
use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\actions\SlugGeneratorAction;
use rokorolov\parus\admin\exceptions\LogicException;
use vova07\imperavi\actions\UploadAction as ImperaviUpload;
use vova07\imperavi\actions\GetAction as ImperaviGet;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * PageController implements the CRUD actions for Page model.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $commandBus;
    private $accessControl;
    private $pageReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        PageReadRepository $pageReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->pageReadRepository = $pageReadRepository;
        $this->accessControl = $accessControl;
        parent::__construct($id, $module, $config);
    }
    
    /**
     * @inheritdoc
     */
    public function actions() 
    {
        return [
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => Settings::imageUploadSrc(),
                'path' => Settings::imageUploadPath(),
                'unique' => false,
            ],
             'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => Settings::imageUploadSrc(),
                'path' => Settings::imageUploadPath(),
                'type' => ImperaviGet::TYPE_IMAGES,
            ],
            'generate-slug' => [
                'class' => SlugGeneratorAction::className(),
            ]
        ];
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
                            return $this->accessControl->canManagePage();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewPage();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'newstatus'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdatePage($this->findModel(Yii::$app->request->get('id')));
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreatePage();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeletePage($this->findModel(Yii::$app->request->get('id')));
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['generate-slug', 'imperavi-image-upload', 'imperavi-get'],
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
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject('rokorolov\parus\page\models\search\PageSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, ['createdBy', 'modifiedBy']);

        return $this->render('view', [
            'model' => $model,
            'accessControl' => $this->accessControl,
            'viewHelper' => Yii::createObject('rokorolov\parus\page\helpers\ViewHelper')
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = Yii::createObject('rokorolov\parus\page\models\form\PageForm')->setData();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {            
            try {
                $data = $form->getData();
                $command = new CreatePageCommand(
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['content'],
                    $data['language'],
                    $data['home'],
                    $data['view'],
                    $data['reference'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('page', 'Page is successfuly created!'));
                return $this->redirect(['update', 'id' => $command->getId()]);
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('page', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                return $this->redirect(['create']);
            }
        }
        
        return $this->render('create', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, ['createdBy', 'modifiedBy']);
        $form = Yii::createObject('rokorolov\parus\page\models\form\PageForm')->setData($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdatePageCommand(
                    $data['id'],
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['content'],
                    $data['language'],
                    $data['home'],
                    $data['view'],
                    $data['reference'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description']
                ));
                Yii::$app->session->setFlash('success', Module::t('page', 'Page is successfuly updated!'));
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('page', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['update', 'id' => $id]);
        }
        
        return $this->render('update', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';
        
        try {
            $this->commandBus->execute(new DeletePageCommand($id));
            Yii::$app->session->setFlash('success', Module::t('page', 'Page is successfully deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('page', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect('index');
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
            $this->commandBus->execute(new ChangePageStatusCommand($id, $status));
            Yii::$app->session->setFlash('success', Module::t('page', 'Changed status successfully!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('page', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
        if (($model = $this->pageReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('page', 'The requested page does not exist.'));
        }
    }
}
