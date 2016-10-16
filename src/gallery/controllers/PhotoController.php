<?php

namespace rokorolov\parus\gallery\controllers;

use rokorolov\parus\gallery\contracts\GalleryServiceInterface;
use rokorolov\parus\gallery\repositories\PhotoReadRepository;
use rokorolov\parus\gallery\commands\UpdatePhotoCommand;
use rokorolov\parus\gallery\commands\CreatePhotoCommand;
use rokorolov\parus\gallery\commands\DeletePhotoCommand;
use rokorolov\parus\gallery\commands\ChangePhotoStatusCommand;
use rokorolov\parus\gallery\commands\ChangePhotoOrderCommand;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\gallery\services\AccessControlService;
use rokorolov\parus\gallery\Module;
use rokorolov\parus\admin\traits\TaskRedirectTrait;
use rokorolov\parus\admin\traits\AjaxAlertTrait;
use rokorolov\parus\admin\contracts\CommandBusInterface;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;

/**
 * PhotoController implements the CRUD actions for Photo model.
 */
class PhotoController extends Controller
{
    use TaskRedirectTrait;
    use AjaxAlertTrait;
    
    private $service;
    private $commandBus;
    private $accessControl;
    private $photoReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        $id,
        $module,
        GalleryServiceInterface $service,
        PhotoReadRepository $photoReadRepository,
        CommandBusInterface $commandBus,
        AccessControlService $accessControl,
        $config = array()
    ) {
        $this->service = $service;
        $this->photoReadRepository = $photoReadRepository;
        $this->commandBus = $commandBus;
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
                            return $this->accessControl->canManagePhoto();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['newstatus', 'reorder'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canUpdatePhoto();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canCreatePhoto();
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'matchCallback' => function($rule, $action) {
                            return $this->accessControl->canDeletePhoto();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'reorder' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Updates an existing Photo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex($id)
    {
        $album = $this->service->getAlbumWithPhotos($id);
        
        if (Yii::$app->request->post() && !$this->accessControl->canUpdatePhoto()) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        
        if ($this->service->loadPhotos($album, Yii::$app->request->post()) && $this->service->validatePhotos($album)) {
            $result = false;
            foreach($album->photos as $photo) {
                try {
                    $data = $photo->getData();
                    $this->commandBus->execute(new UpdatePhotoCommand (
                        $data['id'],
                        $data['status'],
                        $data['order'],
                        $data['album_id'],
                        $data['translations']
                    ));
                    $result = true;
                } catch (LogicException $e) {
                    Yii::warning($e, 'logic');
                    Yii::$app->session->setFlash('danger', Module::t('gallery', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
                }
            }
            $result && Yii::$app->session->setFlash('success', Module::t('gallery', 'Photos is successfully updated!'));
            return $this->redirect(['update', 'id' => $album->id]);
        }
        
        return $this->render('update', [
            'album' => $album,
            'accessControl' => $this->accessControl,
            'config' => Settings::uploadAlbumConfig($id)
        ]);
    }
    
    /**
     * 
     * @return array
     */
    public function actionCreate()
    {
        $imageFile = UploadedFile::getInstanceByName('imageFile');

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$imageFile) {
            return [];
        }

        $album = Yii::$app->request->getBodyParam('album');
        $rules = Settings::uploadAlbumConfig($album);
        $validate = $this->service->validateImage($imageFile, $rules);

        if (!$validate['error']) {
            $this->commandBus->execute(new CreatePhotoCommand(
                $album,
                Yii::createObject('rokorolov\parus\gallery\helpers\PhotoViewHelper')->getDefaultStatus(),
                null,
                $imageFile
            ));
            return [];
        }
        
        return [
            'error' => $validate['message'],
        ];
    }

    /**
     * Deletes an existing Photo model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new DeletePhotoCommand($id));
            Yii::$app->session->setFlash('success', Module::t('gallery', 'Photo is successfully deleted!'));
        } catch (LogicException $e) {
            Yii::warning($e, 'logic') && $result = 'error';
            Yii::$app->session->setFlash('danger', Module::t('gallery', 'An error has occurred ({error})', ['error' => $e->getMessage()]));
        }

        if (Yii::$app->request->isAjax) {
            return Json::encode(['result' => $result]);
        } else {
            return $this->redirect(['album/index']);
        }
    }
    
    /**
     * 
     * @return type
     */
    public function actionReorder()
    {
        if ($order = Yii::$app->request->getBodyParam('order')) {
            $this->commandBus->execute(new ChangePhotoOrderCommand($order));

            Yii::$app->session->setFlash('success', Module::t('gallery', 'Photo successfully reordered!'));
        }
        return;
    }

    /**
     * 
     * @param integer $id
     * @param integer $status
     * @return type
     */
    public function actionNewstatus($id, $status)
    {
        $result = 'success';

        try {
            $this->commandBus->execute(new ChangePhotoStatusCommand($id, $status));
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
}
