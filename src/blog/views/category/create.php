<?php

use rokorolov\helpers\Html;
use rokorolov\parus\blog\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\blog\models\Category */

$this->params['headerIcon'] = Html::icon('folder-open-o');
$this->title = Module::t('blog', 'Add new Category');
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_CREATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW,
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ]
    ],
]);
?>
<div class="post-create">

    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
    ]) ?>

</div>
