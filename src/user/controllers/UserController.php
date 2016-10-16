<?php

namespace rokorolov\parus\user\controllers;

use rokorolov\parus\user\Module;
use rokorolov\parus\user\services\AccessControlService;
use rokorolov\parus\user\models\search\UserSearch;
use rokorolov\parus\user\repositories\UserReadRepository;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\user\commands\UpdateUserCommand;
use rokorolov\parus\user\commands\CreateUserCommand;
use rokorolov\parus\user\commands\DeleteUserCommand;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * UserController implements the CRUD actions for User model.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $commandBus;
    private $accessControl;
    private $userReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        UserReadRepository $userReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->userReadRepository = $userReadRepository;
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
                            return $this->accessControl->canManageUser();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewUser();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdateUser(['author_id' => Yii::$app->request->get('id')]);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreateUser();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeleteUser(['author_id' => Yii::$app->request->get('id')]);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject(UserSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $accessControl = $this->accessControl;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, ['profile']);

        return $this->render('view', [
            'model' => $model,
            'accessControl' => $this->accessControl,
            'viewHelper' => Yii::createObject('rokorolov\parus\user\helpers\ViewHelper')
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = Yii::createObject('rokorolov\parus\user\models\form\UserCreateForm')->setData();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $data = $form->getData();
                $command = new CreateUserCommand(
                    $data['email'],
                    $data['username'],
                    $data['password'],
                    $data['status'],
                    $data['name'],
                    $data['surname'],
                    $data['language'],
                    $data['role']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('user', 'User account is successfully created!'));
                return $this->redirect(['update', 'id' => $command->getId()]);
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('user', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                return $this->redirect(['create']);
            }
            
        }
        
        return $this->render('create', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, ['profile']);
        $form = Yii::createObject('rokorolov\parus\user\models\form\UserUpdateForm')->setData($model);
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdateUserCommand(
                    $data['id'],
                    $data['email'],
                    $data['username'],
                    $data['new_password'],
                    $data['status'],
                    $data['name'],
                    $data['surname'],
                    $data['language'],
                    $data['role'],
                    $data['model']
                ));
                Yii::$app->session->setFlash('success', Module::t('user', 'Account is successfully updated!'));
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('user', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['update', 'id' => $id]);
        }
        
        return $this->render('update', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new DeleteUserCommand($id));
            Yii::$app->session->setFlash('success', Module::t('user', 'User Account is successfully deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('user', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
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
     * @param array $with
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findModel($id, array $with = [])
    {
        if (($model = $this->userReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('user', 'The requested page does not exist.'));
        }
    }
}
