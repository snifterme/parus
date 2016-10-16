<?php

use rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn;
use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use rokorolov\parus\page\Module;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['headerIcon'] = Html::icon('tablet');
$this->title = Module::t('page', 'Page Manager');
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_NEW => ['enable' => $accessControl->canCreatePage()],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="page-index">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php Pjax::begin([
                'id' => 'pjax-container',
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
                        'attribute' => 'title',
                        'value' => function ($model) {
                            return $model->title_manage_link;
                        },
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'grid-head-title'],
                    ],
                    [
                        'attribute' => 'user_username',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return $model->created_at_date;
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                                'clearBtn' => true,
                            ]
                        ]),
                        'headerOptions' => ['class' => 'grid-head-date text-center sort-ordinal'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'hits',
                        'headerOptions' => ['class' => 'sort-numerical'],
                        'contentOptions' => ['class' => 'text-center'],
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->getStatusOptions(),
                        'content' => function($model, $key) use ($searchModel, $accessControl) {
                            return StatusAction::widget([
                                'key' => $key,
                                'status' => $model->status,
                                'buttons' => $searchModel->getStatusActions(),
                                'pjaxContainer' => 'pjax-container',
                                'enable' => $accessControl->canUpdatePage($model),
                            ]);
                        },
                        'headerOptions' => ['class' => 'grid-head-status'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) use ($accessControl) {
                                return $accessControl->canUpdatePage($model);
                            },
                            'delete' => function ($model, $key, $index) use ($accessControl) {
                                return $accessControl->canDeletePage($model);
                            },
                            'view' => function ($model, $key, $index) use ($accessControl) {
                                return $accessControl->canViewPage();
                            }
                        ],
                        'headerOptions' => ['class' => 'grid-head-action'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>