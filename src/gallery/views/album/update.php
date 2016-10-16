<?php

use rokorolov\parus\gallery\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\gallery\models\Album */

$this->params['headerIcon'] = Html::icon('pencil-square-o');
$this->title = Module::t('gallery', 'Edit album') . ': ' . $model->translate()->name;
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW => [
            'visible' => $accessControl->canCreateAlbum($model)
        ],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ]
]);
?>
<div class="album-update">
    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
        'accessControl' => $accessControl
    ]) ?>
</div>
