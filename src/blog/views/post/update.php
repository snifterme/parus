<?php

use rokorolov\helpers\Html;
use rokorolov\parus\blog\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\blog\models\Post */

$this->params['headerIcon'] = Html::icon('pencil-square-o');
$this->title = Module::t('blog', 'Edit Post') . ': ' . $model->title;

$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW => ['visible' => $accessControl->canCreatePost()],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ],
]);
?>
<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
        'accessControl' => $accessControl,
    ]) ?>

</div>
