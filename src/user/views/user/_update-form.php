<?php

use rokorolov\helpers\Html;
use rokorolov\parus\user\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('user', 'Personal Options') ?></span></a></li>
            <li><a href="#tab-security" data-toggle="tab"><?= Html::icon('unlock') ?> <span class="hidden-xs"><?= Module::t('user', 'Security') ?></span></a></li>
            <li><a href="#tab-meta" data-toggle="tab"><?= Html::icon('flag') ?> <span class="hidden-xs"><?= Module::t('user', 'Meta') ?></span></a></li>
        </ul>

        <div class="user-form tab-content-area">
            <?php $form = ActiveForm::begin([
                'id' => 'form-' . $this->context->id,
                'options' => ['class' => Toolbar::FORM_SELECTOR],
            ]); ?>
            <div class="tab-content tab-content-form">

                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    <?php if (!$accessControl->isSuperAdmin($model->id))
                        echo $form->field($model, 'role')->widget(Select2::classname(), [
                            'data' => $model->getRoleOptions(),
                        ]);
                    ?>
                    <?= $form->field($model->profile, 'language')->widget(Select2::classname(), [
                        'data' => $model->profile->languageOptions,
                    ]); ?>
                        
                </div>
                <div id="tab-security" class="tab-pane">
                    <?= $form->field($model, 'current_password')->passwordInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => true]) ?>
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
                        <?= $form->field($model, 'last_login_on', [
                            'inputTemplate' => '<div class="row"><div class="col-sm-8">{input}</div></div>',
                        ])->textInput(['readonly' => true]); ?>
                        <?= $form->field($model, 'last_login_ip', [
                            'inputTemplate' => '<div class="row"><div class="col-sm-8">{input}</div></div>',
                        ])->textInput(['readonly' => true]); ?>

                        <?php
                            $form->layout = $formLayout;
                        ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="form-toolbar">
                <?= $toolbar ?>
                <p><?= Module::t('user', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
            </div>
        </div>
    </div>
    <aside class="col-md-4">
        <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('user', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
                <p><?= Module::t('user', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
                <?php
                    if ($accessControl->canDeleteUser(['author_id' => $model->id])) {
                        echo Html::a(Html::icon('trash') . ' ' . Module::t('user', 'Delete User'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-gray btn-hover-danger',
                            'data-confirm' => Module::t('user', 'Are you sure you want to delete this user?'),
                            'data-method' => 'post'
                        ]);
                    }
                ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('info') . ' ' . Module::t('user', 'Publishing info') ?> </div>
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
                                    'attribute' => 'last_login_on',
                                    'value' => $model->getLastLoginOnMediumWithRelative(),
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'last_login_ip',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format' => 'raw',
                                ],
                        ],
                    ]) ?>
            </div>
        </div>
    </aside>
</div>