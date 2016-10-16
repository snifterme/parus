<?php

use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn;
use rokorolov\parus\gallery\Module;
use rokorolov\helpers\Html;
use rokorolov\parus\gallery\GalleryAsset;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel rokorolov\parus\gallery\models\search\AlbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

GalleryAsset::register($this);

$this->params['headerIcon'] = Html::icon('camera');
$this->title = Module::t('gallery', 'Gallery manager');
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_NEW => [
            'enable' => $accessControl->canCreateAlbum()
        ],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="album-index">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php Pjax::begin([
                'id' => 'pjax-container'
            ]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'id' => $this->context->id . '-grid',
                    'options' => ['class' => 'grid-view table-responsive'],
                    'columns' => [

                        [
                            'attribute' => 'id',
                            'headerOptions' => ['class' => 'grid-head-id text-center sort-numerical'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->title_manage_link;
                            },
                            'headerOptions' => ['class' => 'grid-head-title'],
                        ],
                        [
                            'attribute' => 'status',
                            'filter' => $searchModel->getStatusOptions(),
                            'content' => function($model, $key) use ($searchModel, $accessControl){
                                return StatusAction::widget([
                                    'key' => $key,
                                    'status' => $model->status,
                                    'buttons' => $searchModel->getStatusActions(),
                                    'pjaxContainer' => 'pjax-container',
                                    'enable' => $accessControl->canUpdateAlbum($model),
                                ]);
                            },
                            'headerOptions' => ['class' => 'grid-head-status'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'photo_count',
                            'filter' => false,
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{manage_album_photos}{update}{delete}',
                            'buttons' => [
                                'manage_album_photos' => function($url, $model, $key) {
                                    return [
                                        'icon' => Html::icon('pencil', ['class' => 'text-success fa-fw']) . ' ' . Module::t('gallery', 'Edit photos'),
                                        'url' => ['photo/index', 'id' => $key],
                                        'linkOptions' => ['data-pjax' => '0']
                                    ];
                                }
                            ],
                            'visibleButtons' => [
                                'update' => function ($model, $key, $index) use ($accessControl) {
                                    return $accessControl->canUpdateAlbum();
                                },
                                'delete' => function ($model, $key, $index) use ($accessControl) {
                                    return $accessControl->canDeleteAlbum();
                                },
                                'manage_album_photos' => function ($model, $key, $index) use ($accessControl) {
                                    return $accessControl->canManagePhoto();
                                }
                            ],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
