<?php

use rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn;
use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\helpers\Html;
use rokorolov\parus\language\Module;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['headerIcon'] = Html::icon('language');
$this->title = Module::t('language', 'Language Manager');

?>
<div class="language-index">
    <div class="row">
        <div class="col-sm-3">
            <?= $this->render('_quickForm', [
                'model' => $model,
                'accessControl' => $accessControl
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
                            'id' => 'grid-' . $this->context->id,
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
                                        return $model->title_link;
                                    },
                                    'format' => 'raw',
                                    'headerOptions' => ['class' => 'grid-head-title'],
                                ],
                                [
                                    'attribute' => 'lang_code',
                                    'contentOptions' => ['class' => 'text-center'],
                                    'headerOptions' => ['class' => 'sort-numerical'],
                                ],
                                [
                                    'attribute' => 'order',
                                    'contentOptions' => ['class' => 'text-center'],
                                    'headerOptions' => ['class' => 'sort-numerical'],
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
                                            'enable' => $accessControl->canUpdateLanguage(),
                                        ]);
                                    },
                                    'headerOptions' => ['class' => 'grid-head-status'],
                                    'contentOptions' => ['class' => 'text-center'],
                                ],
                                [
                                    'class' => ActionColumn::className(),
                                    'deleteMessage' => Module::t('language', 'When you delete a language, all related translations in the database will be deleted. Are you sure you want to proceed?'),
                                    'buttons' => [
                                        'update' => function($url, $model, $key) {
                                            return [
                                                'label' => Module::t('language', 'Edit'),
                                                'icon' => Html::icon('pencil', ['class' => 'text-success fa-fw']),
                                                'url' => ['', 'id' => $key],
                                                'linkOptions' => ['data-pjax' => '0']
                                            ];
                                        }
                                    ],
                                    'visibleButtons' => [
                                        'update' => function ($model, $key, $index) use ($accessControl) {
                                            return $accessControl->canUpdateLanguage();
                                        },
                                        'delete' => function ($model, $key, $index) use ($accessControl) {
                                            return $accessControl->canDeleteLanguage($model);
                                        },
                                        'view' => function ($model, $key, $index) use ($accessControl) {
                                            return $accessControl->canViewLanguage();
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