<?php

use rokorolov\parus\menu\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->params['headerIcon'] = Html::icon('bars');
$this->title = Module::t('menu', 'Add new menu type');
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_CREATE,
        Toolbar::BUTTON_SAVE_CLOSE => [
            'target' => Url::to(['menu/index'])
        ],
        Toolbar::BUTTON_SAVE_NEW => [
            'target' => Url::to(['menu-type/create'])
        ],
        Toolbar::BUTTON_CANCEL => [
            'url' => Url::to(['menu/index']),
            'style' => 'gray'
        ]
    ],
]);
?>
<div class="menu-type-create">

    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
        'accessControl' => $accessControl
    ]) ?>

</div>
