<?php

namespace rokorolov\parus\menu\controllers;

use rokorolov\parus\menu\repositories\MenuReadRepository;
use rokorolov\parus\menu\services\AccessControlService;
use rokorolov\parus\menu\Module;
use rokorolov\parus\menu\actions\LinkPickerAction;
use rokorolov\parus\menu\commands\CreateMenuCommand;
use rokorolov\parus\menu\commands\UpdateMenuCommand;
use rokorolov\parus\menu\commands\DeleteMenuCommand;
use rokorolov\parus\menu\commands\ChangeMenuStatusCommand;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * MenuController implements the CRUD actions for Menu model.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $commandBus;
    private $accessControl;
    private $menuReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        MenuReadRepository $menuReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->menuReadRepository = $menuReadRepository;
        $this->accessControl = $accessControl;
        parent::__construct($id, $module, $config);
    }
    
    /**
     * @inheritdoc
     */
    public function actions() 
    {
        return [
            'linkpicker' => [
                'class' => LinkPickerAction::class,
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
                            return $this->accessControl->canManageMenu();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canViewMenu();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'newstatus'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdateMenu();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreateMenu();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeleteMenu($this->findModel(Yii::$app->request->get('id')));
                        }
                    ],
                    [
                        'actions' => ['linkpicker', 'link', 'linkparent'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'newstatus' => ['post'],
                    'linkpicker' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex($menutype = null)
    {
        $searchModel = Yii::createObject('rokorolov\parus\menu\models\search\MenuSearch', [$menutype]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, ['menuType']);
        
        return $this->render('view', [
            'model' => $model,
            'accessControl' => $this->accessControl,
            'viewHelper' => Yii::createObject('rokorolov\parus\menu\helpers\MenuViewHelper')
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($menutype)
    {
        $form = Yii::createObject('rokorolov\parus\menu\models\form\MenuForm')->setData();
        $form->menu_type_id = $menutype;
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            
            try {
                $data = $form->getData();
                $command = new CreateMenuCommand(
                    $data['menu_type_id'],
                    $data['status'],
                    $data['parent_id'],
                    $data['title'],
                    $data['link'],
                    $data['note'],
                    $data['language']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('menu', 'Menu Item is successfuly created!'));
                return $this->redirect(['update', 'id' => $command->getId()]);
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('menu', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                return $this->redirect(['create', 'menutype' => $menutype]);
            }
            
        }
        
        return $this->render('create', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form = Yii::createObject('rokorolov\parus\menu\models\form\MenuForm')->setData($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
          
            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdateMenuCommand(
                    $data['id'],
                    $data['menu_type_id'],
                    $data['status'],
                    $data['parent_id'],
                    $data['title'],
                    $data['link'],
                    $data['note'],
                    $data['position'],
                    $data['language']
                ));
                Yii::$app->session->setFlash('success', Module::t('menu', 'Menu Item is successfuly updated!'));
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('menu', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
            }
            
            return $this->redirect(['update', 'id' => $id]);
        }
        
        return $this->render('update', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }
    
    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';
        
        try {
            $command = new DeleteMenuCommand($id);
            $this->commandBus->execute($command);
            Yii::$app->session->setFlash('success', Module::t('menu', 'Menu Item is successfuly deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('menu', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect(['index', 'menutype' => $command->model->menu_type_id]);
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
            $this->commandBus->execute(new ChangeMenuStatusCommand($id, $status));
            Yii::$app->session->setFlash('success', Module::t('menu', 'Changed status successfully!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('menu', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    
    /**
     * @return mixed
     */
    public function actionLink()
    {
        return $this->renderAjax('_linkForm', [
            'linkTypeOptions' => Yii::createObject('rokorolov\parus\menu\helpers\MenuViewHelper')->getMenuTypeOptions()
        ]);
    }
    /**
     * 
     * @param integer $menutype
     * @param integer $id
     * @return string 
     */
    public function actionLinkparent($menutype)
    {
        $menuItems = $this->menuReadRepository->findChildrenListAsArray($menutype); 
        
        return  Json::encode(Yii::createObject('rokorolov\parus\menu\helpers\MenuViewHelper')->transformMenuItemsForOptions($menuItems));
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
        if (($model = $this->menuReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('menu', 'The requested page does not exist.'));
        }
    }
}