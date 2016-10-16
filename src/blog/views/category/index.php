<?php

use rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn;
use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use rokorolov\parus\blog\Module;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel rokorolov\parus\blog\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['headerIcon'] = Html::icon('folder-open-o');
$this->title = Module::t('blog', 'Category Manager');
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_NEW => ['enable' => $accessControl->canCreateCategory()],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="category-index">
    <div class="row">
        <div class="col-sm-3">
            <?= $this->render('_quickForm', [
                'model' => $model,
                'accessControl' => $accessControl,
            ]) ?>
        </div>
        <div class="col-sm-9">
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
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->title_manage_nested_link;
                                },
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
                                        'autoclose' => true,
                                        'format' => 'dd-mm-yyyy',
                                        'todayHighlight' => true,
                                        'clearBtn' => true,
                                    ]
                                ]),
                                'headerOptions' => ['class' => 'grid-head-date text-center sort-ordinal'],
                                'contentOptions' => ['class' => 'text-center'],
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
                                        'enable' => $accessControl->canUpdatePost($model),
                                    ]);
                                },
                                'headerOptions' => ['class' => 'grid-head-status'],
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'visibleButtons' => [
                                    'update' => function ($model, $key, $index) use ($accessControl) {
                                        return $accessControl->canUpdateCategory($model);
                                    },
                                    'delete' => function ($model, $key, $index) use ($accessControl) {
                                        return $accessControl->canDeleteCategory($model);
                                    },
                                    'view' => function ($model, $key, $index) use ($accessControl) {
                                        return $accessControl->canViewCategory();
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
    </div>
</div>