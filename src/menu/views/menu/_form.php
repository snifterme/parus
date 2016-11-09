<?php

use rokorolov\parus\menu\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('menu', 'Menu') ?></span></a></li>
            <li><a href="#tab-relations" data-toggle="tab"><?= Html::icon('cubes') ?> <span class="hidden-xs"><?= Module::t('menu', 'Relationship') ?></span></a></li>
            <li><a href="#tab-meta" data-toggle="tab"><?= Html::icon('flag') ?> <span class="hidden-xs"><?= Module::t('menu', 'Meta') ?></span></a></li>
        </ul>

        <div class="post-form tab-content-area">
            <?php $form = ActiveForm::begin([
                'id' => $this->context->id . '-form',
                'options' => ['enctype' => 'multipart/form-data', 'class' => Toolbar::FORM_SELECTOR . ' form-label-left']
            ]); ?>
            <div class="tab-content tab-content-form">

                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">

                    <?= $form->field($model, 'title', [
                        'inputOptions' => [
                            'class' => 'form-control input-lg',
                            'placeholder' => Module::t('menu', 'Menu Item Name'),
                            'maxlength' => 128,
                        ]
                    ]); ?>

                    <?php
                        $linkButton = Html::button(Module::t('menu', 'Select'), ['value' => Url::to(['link']), 'class' => 'btn btn-info modalButton']);
                    ?>

                    <?= $form->field($model, "link", [
                            'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">' . $linkButton . '</span></div>',
                        ])->textInput([
                            'class' => 'form-control js-current-link'
                    ]); ?>
                    
                    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>
                </div>
                <div id="tab-relations" class="tab-pane">
                    <div class="form-horizontal">
                    <?php
                        $formLayout = $form->layout;
                        $form->layout = 'horizontal';
                        $form->fieldConfig['horizontalCssClasses']['label'] = 'col-sm-3';
                        $form->fieldConfig['horizontalCssClasses']['wrapper'] = 'col-sm-8';
                        $form->fieldConfig['horizontalCssClasses']['hint'] = 'col-sm-offset-3 col-sm-8';
                    ?>
                    <?= $form->field($model, 'menu_type_id')->widget(Select2::classname(), [
                        'data' => $model->getMenuTypeOptions(),
                    ]); ?>

                    <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
                        'data' => $model->getParentOptions(),
                    ]); ?>

                    <?= $form->field($model, 'position')->widget(Select2::classname(), [
                        'options' => ['placeholder' => Module::t('menu', 'Select a order') . ' ...'],
                        'data' => !$model->isNewRecord ? $model->getOrderOptions() : [],
                        'disabled' => $model->isNewRecord,
                    ])->hint($model->isNewRecord ? '<span class="text-info">' . Module::t('menu', 'Ordering will be available after saving') . '</span>' : ''); ?>

                    </div>
                </div>
                <div id="tab-meta" class="tab-pane">
                    <div class="form-horizontal">

                        <?= $form->field($model, 'id', [
                            'inputTemplate' => '<div class="row"><div class="col-sm-4">{input}</div></div>',
                        ])->textInput(['readonly' => true]); ?>

                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'options' => [
                                'placeholder' => Module::t('menu', 'Select a status') . ' ...',
                            ],
                            'pluginOptions' => ['minimumResultsForSearch' => -1],
                            'data' => $model->getStatusOptions(),
                        ]); ?>
                        
                        <?= $form->field($model, 'language')->widget(Select2::classname(), [
                            'data' => $model->getLanguageOptions(),
                        ]); ?>
                        
                        <?php
                            $form->layout = $formLayout;
                        ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="form-toolbar">
                <?= $toolbar ?>
            </div>
        </div>
    </div>
    <aside class="col-md-4">
        <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('menu', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
                <p><?= Module::t('menu', 'Current status') ?>: <?= $model->getCurrentStatus() ?></p>
                <?php
                    if (!$model->isNewRecord && $accessControl->canDeleteMenu($model)) {
                        echo Html::a(Html::icon('trash') . ' ' . Module::t('menu', 'Delete Menu'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-gray btn-hover-danger',
                            'data-confirm' => Module::t('menu', 'Are you sure you want to delete this record?'),
                            'data-method' => 'post'
                        ]);
                    }
                ?>
            </div>
        </div>
    </aside>
</div>
<?php
Modal::begin([
   'id' => 'modal',
    'closeButton' => false,
]);
echo "<div id='modalContent'></div>";
Modal::end();

$parentInputId = Html::getInputId($model, 'parent_id');
$menutypeInputId = Html::getInputId($model, 'menu_type_id');
$url = Url::to(['linkparent']);

$this->registerJs("
    $('#$menutypeInputId').on('change', function() {
        var menutype = $(this).val();
        $.post('$url?menutype=' + menutype, function(data) {
            $('#$parentInputId option').each(function() {
                $(this).remove();
            });
            $.each(data, function (idx, obj) {
                $('#$parentInputId').append('<option value=\"' + idx + '\">'+ obj +'</option>');
            });
        }, 'json');
    });
    $('.modalButton').click(function() {
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
");