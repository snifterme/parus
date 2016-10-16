<?php

use rokorolov\parus\page\Module;
use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\page\models\Page */

$this->params['headerIcon'] = Html::icon('tablet');
$this->title = Module::t('page', 'Edit Page') . ': ' . $model->title;

$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW => ['visible' => $accessControl->canCreatePage()],
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
