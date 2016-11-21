<?php

namespace rokorolov\parus\blog\controllers;

use rokorolov\parus\blog\repositories\CategoryReadRepository;
use rokorolov\parus\blog\services\AccessControlService;
use rokorolov\parus\blog\commands\CreateCategoryCommand;
use rokorolov\parus\blog\commands\UpdateCategoryCommand;
use rokorolov\parus\blog\commands\DeleteCategoryCommand;
use rokorolov\parus\blog\commands\ChangeCategoryStatusCommand;
use rokorolov\parus\blog\commands\DeleteCategoryIntroImageCommand;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\Module;
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
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\helpers\Json;

/**
 * CategoryController implements the CRUD actions for Category model.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $commandBus;
    private $accessControl;
    private $categoryReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        CategoryReadRepository $categoryReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->categoryReadRepository = $categoryReadRepository;
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
                'url' => Settings::categoryImageUploadSrc(),
                'path' => Settings::categoryImageUploadPath(),
                'unique' => false,
            ],
             'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => Settings::categoryImageUploadSrc(),
                'path' => Settings::categoryImageUploadPath(),
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
                            return $this->accessControl->canManageCategory();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewCategory();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'newstatus', 'remove-intro-image'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdateCategory($this->findModel(Yii::$app->request->get('id')));
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreateCategory();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeleteCategory($this->findModel(Yii::$app->request->get('id')));
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $form = Yii::createObject('rokorolov\parus\blog\models\form\CategoryForm')->setData();
        
        if (Yii::$app->request->post() && !$this->accessControl->canCreateCategory()) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            
            try {
                $data = $form->getData();
                $command = new CreateCategoryCommand(
                    $data['parent_id'],
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['description'],
                    $data['language'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description'],
                    $data['imageFile']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('blog', 'Category is successfully created!'));
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['index']);
        }
        
        $searchModel = Yii::createObject('rokorolov\parus\blog\models\search\CategorySearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, ['createdBy', 'updatedBy']);

        return $this->render('view', [
            'model' => $model,
            'accessControl' => $this->accessControl,
            'viewHelper' => Yii::createObject('rokorolov\parus\blog\helpers\CategoryViewHelper')
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = Yii::createObject('rokorolov\parus\blog\models\form\CategoryForm')->setData();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            
            try {
                $data = $form->getData();
                $command = new CreateCategoryCommand(
                    $data['parent_id'],
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['description'],
                    $data['language'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description'],
                    $data['imageFile']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('blog', 'Category is successfully created!'));
                return $this->redirect(['update', 'id' => $command->getId()]);
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                return $this->redirect(['create']);
            }
        }
        
        return $this->render('create', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, ['createdBy', 'updatedBy']);
        $form = Yii::createObject('rokorolov\parus\blog\models\form\CategoryForm')->setData($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdateCategoryCommand(
                    $data['id'],
                    $data['parent_id'],
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['description'],
                    $data['language'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description'],
                    $data['imageFile']
                ));
                Yii::$app->session->setFlash('success', Module::t('blog', 'Category is successfully updated!'));
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['update', 'id' => $id]);
        }
        
        return $this->render('update', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';
        
        try {
            $this->commandBus->execute(new DeleteCategoryCommand($id));
            Yii::$app->session->setFlash('success', Module::t('blog', 'Category is successfully deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
            $this->commandBus->execute(new ChangeCategoryStatusCommand($id, $status));
            Yii::$app->session->setFlash('success', Module::t('blog', 'Changed status successfully!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
     * @return type
     */
    public function actionRemoveIntroImage($id)
    {
        $result = 'success';
        
        try {
            $this->commandBus->execute(new DeleteCategoryIntroImageCommand($id));
            Yii::$app->session->setFlash('success', Module::t('blog', 'Removed image successfully!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
        if (($model = $this->categoryReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('blog', 'The requested page does not exist.'));
        }
    }
}