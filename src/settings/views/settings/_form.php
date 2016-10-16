<?php

use rokorolov\parus\settings\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Config */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-8">
<ul class="nav nav-tabs nav-tabs-form">
    <li class="active">
        <a href="#tab-details" data-toggle="tab"><?=  Html::icon('cogs') ?> <span class="hidden-xs"><?= Module::t('settings', 'Site') ?></a>
    </li>
</ul>

<?php $form = ActiveForm::begin([
    'id' => 'form-' . $this->context->id,
    'options' => ['class' => Toolbar::FORM_SELECTOR],
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'wrapper' => 'col-sm-8',
        ],
    ]
]); ?>

<div class="settings-form tab-content-area">
    <div class="tab-content tab-content-form">

        <?= $form->errorSummary($models); ?>

        <div id="tab-details" class="tab-pane active">

            <?php foreach ($models as $index => $setting) : ?>
                <?php if (!($setting->type) || $setting->type == 'string') : ?>
                    <?= $form->field($setting, "[$index]value")->label(Html::encode($setting->label)); ?>
                <?php elseif ($setting->type == 'boolean') : ?>
                    <?= $form->field($setting, "[$index]value")->widget(SwitchInput::className(), [
                        'containerOptions' => ['class' => 'switch'],
                        'pluginOptions' => [
                            'onText' => Module::t('settings', 'Yes'),
                            'offText' => Module::t('settings', 'No'),
                            'onColor' => 'success',
                            'offColor' => 'warning',
                            'size' => 'small'
                    ]])->label(Html::encode($setting->label)) ?>
                <?php elseif ($setting->type == 'text') : ?>
                    <?= $form->field($setting, "[$index]value")->textArea()->label(Html::encode($setting->label)); ?>
                <?php elseif ($setting->type == 'dropdown') : ?>
                    <?= $form->field($setting, "[$index]value")->widget(Select2::classname(), [
                        'options' => ['placeholder' => Module::t('settings', 'Select') . ' ...'],
                        'pluginOptions' => ['minimumResultsForSearch' => -1],
                        'data' => $setting->getOptions(),
                    ])->label(Html::encode($setting->label)); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="form-toolbar">
        <?= $toolbar ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
    </div>
    <aside class="col-md-4">
        <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('settings', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
            </div>
        </div>
    </aside>
</div>
