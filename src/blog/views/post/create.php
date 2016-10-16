<?php

use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\blog\Module;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\blog\models\Post */

$this->params['headerIcon'] = Html::icon('pencil-square-o');
$this->title = Module::t('blog', 'Add new Post');

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
