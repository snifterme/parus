<?php

use rokorolov\parus\gallery\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;


/* @var $this yii\web\View */
/* @var $model rokorolov\parus\gallery\models\Album */

$this->params['headerIcon'] = Html::icon('pencil-square-o');
$this->title = Module::t('gallery', 'Add new album');
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_CREATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW,
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ]
]);
?>

<div class="album-create">

    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
    ]) ?>

</div>
