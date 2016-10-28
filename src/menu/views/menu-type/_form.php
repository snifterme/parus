<?php

use rokorolov\parus\menu\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\menu\models\MenuType */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('menu', 'Menu type') ?></span></a></li>
        </ul>

        <?php $form = ActiveForm::begin([
            'id' => $this->context->id . '-form',
            'options' => ['class' => Toolbar::FORM_SELECTOR]
        ]); ?>
        <div class="menu-type-form tab-content-area">
            <div class="tab-content tab-content-form">
                
                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">
                    
                    <?= $form->field($model, 'title', [
                        'inputOptions' => [
                            'class' => 'form-control input-lg',
                            'placeholder' => Module::t('menu', 'Enter Menu Title'),
                            'maxlength' => 128
                        ]
                    ]); ?>

                    <?= $form->field($model, 'menu_type_alias')->textInput(['maxlength' => 128]) ?>
                    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

                </div>
            </div>
            <div class="form-toolbar">
                <?= $toolbar ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <aside class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('menu', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
                <?php
                    if (!$model->isNewRecord && $accessControl->canDeleteMenuType()) {
                        echo Html::a(Html::icon('trash') . ' ' . Module::t('menu', 'Delete Menu Type'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-gray btn-hover-danger',
                            'data-confirm' => Module::t('menu', 'Are you sure you want to delete this record?'),
                            'data-method' => 'post',
                        ]);
                    }
                ?>
            </div>
        </div>
    </aside>
</div>
