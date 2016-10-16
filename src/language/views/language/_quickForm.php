<?php

use rokorolov\parus\language\Module;
use kartik\switchinput\SwitchInput;
use rokorolov\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form quick-form language-form">
    <div class="form-body">
        <h4 class="title"><?= $model->isNewRecord ? Module::t('language', 'Add new Language') : Module::t('language', 'Edit language'); ?></h4>

        <?php $form = ActiveForm::begin([
            'id' => 'form-' . $this->context->id
        ]); ?>
        
            <?= $form->field($model, "title")->textInput(['maxlength' => 128]); ?>
        
            <?= $form->field($model, 'status')->widget(SwitchInput::className(), [
                'containerOptions' => ['class' => 'switch'],
                'pluginOptions' => [
                    'onText' => Module::t('language', 'Yes'),
                    'offText' => Module::t('language', 'No'),
                    'onColor' => 'success',
                    'offColor' => 'warning',
                    'size' => 'small'
                ]])->label(Module::t('language', 'Published') . '?')
            ?>
        
            <?= $form->field($model, 'lang_code', [
                'inputTemplate' => '<div class="row"><div class="col-sm-10">{input}</div></div>'
            ])->textInput(['maxlength' => 7]) ?>
        
            <?= $form->field($model, 'date_format', [
                'inputTemplate' => '<div class="row"><div class="col-sm-10">{input}</div></div>'
            ])->textInput(['maxlength' => 32]) ?>
        
            <?= $form->field($model, 'date_time_format', [
                'inputTemplate' => '<div class="row"><div class="col-sm-10">{input}</div></div>'
            ])->textInput(['maxlength' => 32]) ?>
        
            <?= $form->field($model, 'order', [
                'inputTemplate' => '<div class="row"><div class="col-sm-6">{input}</div></div>'
            ])->textInput() ?>

            <div class="form-group">
                <?php
                    if ($model->isNewRecord) {
                        echo $accessControl->canCreateLanguage() ? Html::submitButton(Html::icon('plus') . ' ' . Module::t('language', 'Add new Language'), ['class' =>  'btn btn-primary']) : Html::button(Html::icon('plus') . ' ' . Module::t('language', 'Add new Language'), ['class' =>  'btn btn-primary disabled']);
                    } else {
                        echo $accessControl->canUpdateLanguage() ? Html::submitButton(Html::icon('pencil') . ' '  . Module::t('language', 'Update'), ['class' =>  'btn btn-primary']) : Html::button(Html::icon('pencil') . ' '  . Module::t('language', 'Update'), ['class' =>  'btn btn-primary disabled']);
                    }
                ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
