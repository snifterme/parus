<?php

use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use rokorolov\parus\language\Module;
use yii\widgets\DetailView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model rokorolov\parus\language\models\Language */

$this->params['headerIcon'] = Html::icon('language');
$this->title = Module::t('language', 'View Language') . ': ' . $model->title;
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE_CUSTOM => [
            'url' => ['index', 'id' => $model->id],
            'visible' => $accessControl->canUpdateLanguage()
        ],
        Toolbar::BUTTON_DELETE => [
            'url' => ['delete', 'id' => $model->id],
            'linkOptions' => [
                'data-confirm' => Module::t('language', 'When you delete a language, all related translations in the database will be deleted. Are you sure you want to proceed?'),
                'data-method' => 'post'
            ],
            'visible' => $accessControl->canDeleteLanguage($model)
        ],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="language-view">
    <div class="tab-nav tab-view">
        <ul class="nav nav-pills">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('home') . ' ' . Module::t('language', 'Details') ?></a></li>
        </ul>
    </div>
    <div class="tab-content tab-content-view">
        <div id="tab-details" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"><?= Html::icon('info') . ' ' . Module::t('language', 'Information') ?> </h5></div>
                        <div class="panel-body">
                        <?php Pjax::begin([
                            'id' => 'pjax-container',
                        ]); ?>
                        <?= DetailView::widget([
                            'model' => $model,
                            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                            'attributes' => [
                                [
                                    'attribute' => 'title',
                                    'format' => 'raw',
                                    'label' => $viewHelper->getAttributeLabel('title'),
                                    'value' => $model->title_link,
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
                                        'enable' => $accessControl->canUpdateLanguage(),

                                    ]),
                                ],
                                [
                                    'attribute' => 'lang_code',
                                    'label' => $viewHelper->getAttributeLabel('lang_code'),
                                ],
                                [
                                    'attribute' => 'image',
                                    'label' => $viewHelper->getAttributeLabel('image'),
                                ],
                                [
                                    'attribute' => 'order',
                                    'label' => $viewHelper->getAttributeLabel('order'),
                                ],
                                [
                                    'attribute' => 'date_format',
                                    'label' => $viewHelper->getAttributeLabel('date_format'),
                                ],
                                [
                                    'attribute' => 'date_time_format',
                                    'label' => $viewHelper->getAttributeLabel('date_time_format'),
                                ],
                            ]]) ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"> <?= Html::icon('pencil-square-o') . ' ' . Module::t('language', 'Publishing info') ?></h5></div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'id',
                                        'label' => $viewHelper->getAttributeLabel('id')
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'raw',
                                        'value' => $model->created_at_medium_with_relative,
                                        'label' => $viewHelper->getAttributeLabel('created_at')
                                    ],
                                    [
                                        'attribute' => 'modified_at',
                                        'format' => 'raw',
                                        'value' => $model->modified_at_medium_with_relative,
                                        'label' => $viewHelper->getAttributeLabel('modified_at')
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>