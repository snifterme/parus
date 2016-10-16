<?php

namespace rokorolov\parus\menu\controllers;

use rokorolov\parus\menu\repositories\MenuTypeReadRepository;
use rokorolov\parus\menu\services\AccessControlService;
use rokorolov\parus\menu\commands\CreateMenuTypeCommand;
use rokorolov\parus\menu\commands\UpdateMenuTypeCommand;
use rokorolov\parus\menu\commands\DeleteMenuTypeCommand;
use rokorolov\parus\menu\Module;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * MenuTypeController implements the CRUD actions for MenuType model.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeController extends Controller
{
    use TaskRedirectTrait;
    
    private $commandBus;
    private $accessControl;
    private $menuTypeReadRepository;
    
    public function __construct(
        $id,
        $module,
        CommandBusInterface $commandBus,
        MenuTypeReadRepository $menuTypeReadRepository,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->commandBus = $commandBus;
        $this->menuTypeReadRepository = $menuTypeReadRepository;
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
                        'actions' => ['update'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdateMenuType();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreateMenuType();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeleteMenuType();
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
     * Creates a new MenuType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = Yii::createObject('rokorolov\parus\menu\models\form\MenuTypeForm')->setData();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $data = $form->getData();
                $command = new CreateMenuTypeCommand(
                    $data['title'],
                    $data['menu_type_aliase'],
                    $data['description']
                );
                $this->commandBus->execute($command);
                Yii::$app->session->setFlash('success', Module::t('menu', 'Menu Type is successfuly created!'));
                return $this->redirect(['update', 'id' => $command->getId()]);
            } catch (LogicException $e) {
                Yii::warning($e, 'logic');
                Yii::$app->session->setFlash('danger', Module::t('menu', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                return $this->redirect(['create']);
            }
            
        }
        
        return $this->render('create', [
            'model' => $form,
            'accessControl' => $this->accessControl
        ]);
    }

    /**
     * Updates an existing MenuType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $form = Yii::createObject('rokorolov\parus\menu\models\form\MenuTypeForm')->setData($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
          
            try {
                $data = $form->getData();
                $this->commandBus->execute(new UpdateMenuTypeCommand(
                    $data['id'],
                    $data['title'],
                    $data['menu_type_aliase'],
                    $data['description']
                ));
                Yii::$app->session->setFlash('success', Module::t('menu', 'Menu Type is successfuly updated!'));
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
     * Deletes an existing MenuType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new DeleteMenuTypeCommand($id));
            Yii::$app->session->setFlash('success', Module::t('menu', 'Menu Type is successfuly deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('menu', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect(['menu/index']);
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
        if (($model = $this->menuTypeReadRepository->findById($id, $with)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('menu', 'The requested page does not exist.'));
        }
    }
}
