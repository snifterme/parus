<?php

namespace rokorolov\parus\blog\controllers;

use rokorolov\parus\blog\repositories\PostReadRepository;
use rokorolov\parus\blog\services\AccessControlService;
use rokorolov\parus\blog\commands\CreatePostCommand;
use rokorolov\parus\blog\commands\UpdatePostCommand;
use rokorolov\parus\blog\commands\DeletePostCommand;
use rokorolov\parus\blog\commands\ChangePostStatusCommand;
use rokorolov\parus\blog\commands\DeletePostIntroImageCommand;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\Module;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\actions\SlugGeneratorAction;
use rokorolov\parus\admin\contracts\CommandBusInterface;
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
 * PostController implements the CRUD actions for Post model.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;

    private $commandBus;
    private $accessControl;
    private $postReadRepository;

    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        PostReadRepository $postReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->postReadRepository = $postReadRepository;
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
                'url' => Settings::postImageUploadSrc(),
                'path' => Settings::postImageUploadPath(),
                'unique' => false,
            ],
             'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => Settings::postImageUploadSrc(),
                'path' => Settings::postImageUploadPath(),
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
                            return $this->accessControl->canManagePost();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewPost();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'newstatus', 'remove-intro-image'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdatePost($this->findModel(Yii::$app->request->get('id')));
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreatePost();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeletePost($this->findModel(Yii::$app->request->get('id')));
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
                    'generate-slug' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject('rokorolov\parus\blog\models\search\PostSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, ['category', 'createdBy', 'updatedBy']);

        return $this->render('view', [
            'model' => $model,
            'accessControl' => $this->accessControl,
            'viewHelper' => Yii::createObject('rokorolov\parus\blog\helpers\PostViewHelper')
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = Yii::createObject('rokorolov\parus\blog\models\form\PostForm')->setData();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $data = $form->getData();
                $command = new CreatePostCommand(
                    $data['category_id'],
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['introtext'],
                    $data['fulltext'],
                    $data['language'],
                    $data['published_at'],
                    $data['publish_up'],
                    $data['publish_down'],
                    $data['view'],
                    $data['reference'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description'],
                    $data['imageFile']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('blog', 'Post is successfully created!'));
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
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, ['createdBy', 'updatedBy']);
        $form = Yii::createObject('rokorolov\parus\blog\models\form\PostForm')->setData($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdatePostCommand(
                    $data['id'],
                    $data['category_id'],
                    $data['status'],
                    $data['title'],
                    $data['slug'],
                    $data['introtext'],
                    $data['fulltext'],
                    $data['language'],
                    $data['published_at'],
                    $data['publish_up'],
                    $data['publish_down'],
                    $data['view'],
                    $data['reference'],
                    $data['meta_title'],
                    $data['meta_keywords'],
                    $data['meta_description'],
                    $data['imageFile']
                ));
                Yii::$app->session->setFlash('success', Module::t('blog', 'Post is successfully updated!'));
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
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new DeletePostCommand($id));
            Yii::$app->session->setFlash('success', Module::t('blog', 'Post is successfully deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('blog', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
            $this->commandBus->execute(new ChangePostStatusCommand($id, $status));
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
            $this->commandBus->execute(new DeletePostIntroImageCommand($id));
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
        if (($model = $this->postReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('blog', 'The requested page does not exist.'));
        }
    }
}
