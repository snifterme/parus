<?php

use rokorolov\helpers\Html;
use kartik\select2\Select2;

?>

<div class="form-group">
    <?= Html::label($label); ?>
    <?= Select2::widget([
        'id' => 'link-item',
        'name' => 'item',
        'value' => $link,
        'data' => $data,
        'options' => [
            'placeholder' => $placeholder,
        ],
    ]); ?>
</div>

<?php 

$this->registerJs("
     $('#link-item').on('change', function() {
        var value = $(this).val();
        if (value) {
            $('#form-link').val(value);
        }
    });
");

?>