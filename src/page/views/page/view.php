<?php

use rokorolov\parus\page\Module;
use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use yii\bootstrap\Collapse;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\page\models\Page */

$this->params['headerIcon'] = Html::icon('pencil-square-o');
$this->title = Module::t('page', 'View Page') . ': ' . $model->title;
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE_CUSTOM => [
            'url' => ['update', 'id' => $model->id],
            'visible' => $accessControl->canUpdatePage($model)
        ],
        Toolbar::BUTTON_DELETE => [
            'url' => ['delete', 'id' => $model->id],
            'visible' => $accessControl->canDeletePage($model)
        ],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="post-view">
    <div class="tab-nav tab-view">
        <ul class="nav nav-pills">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('home') . ' ' . Module::t('page', 'Details') ?></a></li>
            <li><a href="#tab-content" data-toggle="tab"><?= Html::icon('pencil') . ' ' . Module::t('page', 'Content') ?></a></li>
        </ul>
    </div>

    <div class="tab-content tab-content-view">
        <div id="tab-details" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"><?= Html::icon('info') . ' ' . Module::t('page', 'Information') ?> </h5></div>
                        <div class="panel-body">
                        <?php Pjax::begin([
                            'id' => 'pjax-container',
                        ]); ?>
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'title',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('title'),
                                    'value' => Html::a(Html::encode($model->title), ['update', 'id' => $model->id], ['data-pjax' => 0, 'class' => 'grid-title-link']),
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('status'),
                                    'value' => StatusAction::widget([
                                        'key' => $model->id,
                                        'status' => $model->status,
                                        'buttons' => $viewHelper->getStatusActions(),
                                        'pjaxContainer' => 'pjax-container',
                                        'enable' => $accessControl->canUpdatePage($model),

                                    ]),
                                ],
                                [
                                    'attribute' => 'slug',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('slug')
                                ],
                                [
                                    'attribute' => 'hits',
                                    'label' => $viewHelper->getAttributeLabel('hits'),
                                    'format' => 'raw',
                                ],
                            ]]) ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"><?= Html::icon('signal') . ' ' . Module::t('page', 'Seo') ?></h5></div>
                        <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'meta_title',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('meta_title')
                                ],
                                [
                                    'attribute' => 'meta_keywords',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('meta_keywords')
                                ],
                                [
                                    'attribute' => 'meta_description',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('meta_description')
                                ],
                            ],
                        ]) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"> <?= Html::icon('pencil-square-o') . ' ' . Module::t('page', 'Publishing info') ?></h5></div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'id',
                                        'label' => $viewHelper->getAttributeLabel('id')
                                    ],
                                    [
                                        'attribute' => 'createdBy.username',
                                        'label' => $viewHelper->getAttributeLabel('created_by')
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'raw',
                                        'value' => $model->created_at_medium_with_relative,
                                        'label' => $viewHelper->getAttributeLabel('created_at')
                                    ],
                                    [
                                        'attribute' => 'updatedBy.username',
                                        'label' => $viewHelper->getAttributeLabel('updated_by')
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'format' => 'raw',
                                        'value' => $model->updated_at_medium_with_relative,
                                        'label' => $viewHelper->getAttributeLabel('updated_at')
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tab-content" class="tab-pane">
            <div class="row">
                <div class="col-md-12">
                     <?= Collapse::widget([
                        'items' => [
                            [
                                'label' => $viewHelper->getAttributeLabel('content'),
                                'contentOptions' => ['class' => 'in'],
                                'content' => $model->content,
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</div>
