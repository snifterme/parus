<?php

use rokorolov\parus\page\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\admin\theme\widgets\sluggable\SluggableButton;
use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\Redactor;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('page', 'Page') ?></span></a></li>
            <li><a href="#tab-seo" data-toggle="tab"><?= Html::icon('signal') ?> <span class="hidden-xs"><?= Module::t('page', 'Seo') ?></span></a></li>
            <li><a href="#tab-meta" data-toggle="tab"><?= Html::icon('flag') ?> <span class="hidden-xs"><?= Module::t('page', 'Meta') ?></span></a></li>
        </ul>

        <div class="page-form tab-content-area">
            <?php $form = ActiveForm::begin([
                'id' => $this->context->id . '-form',
                'options' => ['class' => Toolbar::FORM_SELECTOR . ' form-label-left']
            ]); ?>
            <div class="tab-content tab-content-form">

                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">

                    <?= $form->field($model, 'title', [
                        'inputOptions' => [
                            'class' => 'form-control input-lg',
                            'placeholder' => Module::t('page', 'Enter Page Title'),
                            'maxlength' => true,
                        ]
                    ]); ?>

                    <?= $form->field($model, 'slug', [
                        'inputOptions' => ['title' => $model->slug],
                        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'
                                . SluggableButton::widget([
                                    'selectorFrom' => "pageform-title",
                                    'selectorTo' => "pageform-slug",
                                    'clickEvent' => 'generate-slug-',
                                    'options' => [
                                        'class' => 'btn-info',
                                    ]
                                ])
                        . '</span></div>',
                    ])->textInput(['maxlength' => true]); ?>
                    
                    <?= $form->field($model, 'content')->widget(Redactor::class); ?>
                    
                </div>
                <div id="tab-seo" class="tab-pane">

                    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]); ?>
                    <?= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]); ?>
                    <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]); ?>

                </div>
                <div id="tab-meta" class="tab-pane">
                    <div class="form-horizontal">
                        <?php
                            $formLayout = $form->layout;
                            $form->layout = 'horizontal';
                            $form->fieldConfig['horizontalCssClasses']['label'] = 'col-sm-4';
                            $form->fieldConfig['horizontalCssClasses']['wrapper'] = 'col-sm-8';
                        ?>
                        <?= $form->field($model, 'id', [
                            'inputTemplate' => '<div class="row"><div class="col-sm-4">{input}</div></div>',
                        ])->textInput(['readonly' => true]); ?>

                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'options' => [
                                'placeholder' => Module::t('page', 'Select a status') . ' ...',
                            ],
                            'pluginOptions' => ['minimumResultsForSearch' => -1],
                            'data' => $model->getStatusOptions(),
                        ]); ?>

                        <?= $form->field($model, 'language')->widget(Select2::classname(), [
                            'data' => $model->getLanguageOptions(),
                        ]); ?>
        
                        <?= $form->field($model, 'home')->widget(SwitchInput::className(), [
                            'containerOptions' => ['class' => 'switch'],
                            'pluginOptions' => [
                                'onText' => Module::t('page', 'Yes'),
                                'offText' => Module::t('page', 'No'),
                                'onColor' => 'success',
                                'offColor' => 'warning',
                                'size' => 'small'
                            ]])->label(Module::t('page', 'Home'))
                        ?>
                        
                        <?= $form->field($model, 'view')->textInput(['maxlength' => true]); ?>

                        <?= $form->field($model, 'reference')->textInput(['maxlength' => true]); ?>

                        <?= $form->field($model, 'version')->textInput(['readonly' => true]); ?>
                        
                        <?php
                            $form->layout = $formLayout;
                        ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="form-toolbar">
                <?= $toolbar ?>
                <p><?= Module::t('page', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
            </div>
        </div>
    </div>
    <aside class="col-md-4">
        <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('page', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
                <p><?= Module::t('page', 'Current status') ?>: <?= $model->getCurrentStatus() ?></p>
                <p><?= Module::t('page', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
                <?php
                    if (!$model->isNewRecord && $accessControl->canDeletePage($model)) {
                        echo Html::a(Html::icon('trash') . ' ' . Module::t('page', 'Delete Page'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-gray btn-hover-danger',
                            'data-confirm' => Module::t('page', 'Are you sure you want to delete this record?'),
                            'data-method' => 'post'
                        ]);
                    }
                ?>
            </div>
        </div>
        <?php if (!$model->isNewRecord) : ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?= Html::icon('info') . ' ' . Module::t('page', 'Publishing info') ?> </div>
                </div>
                <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped detail-view'],
                            'attributes' => [
                                [
                                    'attribute' => 'id',
                                ],
                                [
                                    'attribute' => 'created_by',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'updated_by',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format' => 'raw',
                                ],
                            ],
                        ]) ?>
                </div>
            </div>
        <?php endif; ?>
    </aside>
</div>
