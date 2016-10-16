<?php

use rokorolov\parus\blog\Module;
use rokorolov\helpers\Html;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\blog\models\Category */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="form quick-form category-form">
    <div class="form-body">
        <h4 class="title"><?= Module::t('blog', 'Add new Category'); ?></h4>

        <?php $form = ActiveForm::begin([
            'id' => $this->context->id . '-form',
        ]); ?>

            <?= $form->field($model, 'title', [
                'inputOptions' => [
                    'maxlength' => 128,
                ]
            ]); ?>

            <?= $form->field($model, 'slug', [
                'inputTemplate' => '<div class="row"><div class="col-sm-10">{input}</div></div>',
                'inputOptions' => [
                    'maxlength' => 128,
                ]
            ])->textInput(); ?>
                
            <?php 
                if (empty($model->status)) {
                    $model->status = true;
                }
            ?>
        
            <?= $form->field($model, 'status')->widget(SwitchInput::className(), [
                'containerOptions' => ['class' => 'switch'],
                'pluginOptions' => [
                    'onText' => Module::t('blog', 'Yes'),
                    'offText' => Module::t('blog', 'No'),
                    'onColor' => 'success',
                    'offColor' => 'warning',
                    'size' => 'small'
                ]])->label(Module::t('blog', 'Published') . '?')
            ?>
        
            <?= $form->field($model, 'language', [
                'inputTemplate' => '<div class="row"><div class="col-sm-10">{input}</div></div>'
            ])->widget(Select2::classname(), [
                'data' => $model->getLanguageOptions(),
            ]); ?>
        
            <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
                    'data' => $model->getParentOptions(),
            ]); ?>
        
            <div class="form-group">
                <?php
                    if ($accessControl->canCreateCategory()) {
                        echo Html::submitButton(Html::icon('plus') . ' ' . Module::t('blog', 'Add'), ['class' =>  'btn btn-primary']);
                    } else {
                        echo Html::button(Html::icon('plus') . ' ' . Module::t('blog', 'Add'), ['class' =>  'btn btn-primary disabled']);
                    }
                ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
