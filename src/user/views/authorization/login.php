<?php

use rokorolov\parus\user\Module;
use rokorolov\parus\admin\helpers\Settings;
use rokorolov\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\LoginForm $model
 */

$this->title = Settings::appName();
?>

<div class="wrapper-page">
    <div class="login-logo"><strong><?= Settings::appName() ?></strong></div>
    <div class="login-box">
        <div class="login-box-heading">
            <?= Module::t('user/authorization', 'Login to access your account.') ?>
        </div>
        <div class="login-box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableClientValidation' => false,
            ]); ?>

                <?= $form->field($model, 'username', [
                    'inputTemplate' => '<div class="input-group"><span class="input-group-addon">' . Html::icon('user fa-fw', [], 'span') . '</span>{input}</div>',
                    'inputOptions' => [
                        'placeholder' => Module::t('user/authorization', 'Username')
                    ]
                ])->label(false) ?>

                <?= $form->field($model, 'password', [
                    'inputTemplate' => '<div class="input-group"><span class="input-group-addon">' . Html::icon('lock fa-fw', [], 'span') . '</span>{input}</div>',
                    'inputOptions' => [
                        'placeholder' => Module::t('user/authorization', 'Password')
                    ]
                ])->label(false)->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="login-box-button clearfix form-group">
                    <div class="col-xs-12">
                        <?= Html::submitButton(Module::t('user/authorization', 'Log in'), ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>