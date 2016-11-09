<?php

use rokorolov\helpers\Html;
use rokorolov\parus\user\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('user', 'Personal Options') ?></span></a></li>
            <li><a href="#tab-security" data-toggle="tab"><?= Html::icon('unlock') ?> <span class="hidden-xs"><?= Module::t('user', 'Security') ?></span></a></li>
            <!--<li><a href="#tab-meta" data-toggle="tab"><?php // Html::icon('flag') ?> <span class="hidden-xs"><?php // Module::t('user', 'Meta') ?></span></a></li>-->
        </ul>

        <div class="user-form tab-content-area">
            <?php $form = ActiveForm::begin([
                'id' => 'form-' . $this->context->id,
                'options' => ['class' => Toolbar::FORM_SELECTOR],
            ]); ?>
            <div class="tab-content tab-content-form">

                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">

                    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($model, 'role')->widget(Select2::classname(), [
                        'data' => $model->getRoleOptions(),
                    ]); ?>
                    <?= $form->field($model->profile, 'language')->widget(Select2::classname(), [
                        'data' => $model->profile->languageOptions,
                    ]); ?>
                        
                </div>
                <div id="tab-security" class="tab-pane">
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255]) ?>
                </div>                
                <div id="tab-meta" class="tab-pane">
                    <div class="form-horizontal">
                        <?php
                            $formLayout = $form->layout;
                            $form->layout = 'horizontal';
                            $form->fieldConfig['horizontalCssClasses']['label'] = 'col-sm-4';
                            $form->fieldConfig['horizontalCssClasses']['wrapper'] = 'col-sm-8';
                        ?>

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
            </div>
        </div>
    </aside>
</div>