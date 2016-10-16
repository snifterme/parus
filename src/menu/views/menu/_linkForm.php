<?php

use rokorolov\parus\menu\Module;
use rokorolov\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="link-form">

    <?php $form = ActiveForm::begin([
        'id' => $this->context->id . '-form',
    ]); ?>
    
    <div class="form-group">
        <?= Html::label(Module::t('menu', 'URL'), 'form-link'); ?>
        <?= Html::input('text', 'url', null, ['class' => 'form-control js-link', 'id' => 'form-link']); ?>
    </div>
    
    <div class="form-group">
        <?= Html::label(Module::t('menu', 'Type'), 'form-type'); ?>
        <?= Select2::widget([
            'id' => 'form-type',
            'name' => 'type',
            'data' => $linkTypeOptions,
            'options' => [
                'placeholder' => Module::t('menu', '- Select Type -'),
                'class' => 'js-types',
            ],
            'pluginOptions' => ['minimumResultsForSearch' => -1],
        ]); ?>
    </div>
    
    <div class="hidden js-linkpicker-edit"></div>
    
    <div class="form-group">
        <?= Html::Button(Module::t('menu', 'Update'), ['class' => 'btn btn-success js-resolve-link', 'type' => 'button']) ?>
        <?= Html::submitButton(Module::t('menu', 'Close'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$linkPickerUrl = Url::to(['linkpicker']);
$this->registerJs("
    var types = $('.js-types');
    var link = $('.js-link');
    var currentLink = $('.js-current-link');

    types.on('change', function(e) {
        var link = $(this).val();
        update(link);
    });

    link.on('change', function(e) {
        var link = $(this).val();
        update(link);
    });

    set(currentLink.val());

    function set(url) {
        if(url) {
            link.val(url).trigger('change');
        }
    };

    function update(link) {
        $.post('$linkPickerUrl', {'link': link}, function(data) {
            $('.js-linkpicker-edit').empty().append(data).removeClass('hidden');
            if (link.indexOf('/') > -1) {
                var type = link.substr(0, link.indexOf('/'));
                types.val(type).trigger('change.select2');
            }
        });
        return false;
    };

    $('.js-resolve-link').click(function (e) {
        var linkValue = link.val();
        currentLink.val(linkValue);
        $('#modal').modal('hide');
        return false;
    });
");
?>