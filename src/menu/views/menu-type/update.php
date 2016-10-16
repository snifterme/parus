<?php

use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\menu\Module;
use rokorolov\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\menu\models\MenuType */

$this->params['headerIcon'] = Html::icon('bars');
$this->title = Module::t('menu', 'Edit menu type') . ': ' . $model->title;
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE,
        Toolbar::BUTTON_SAVE_CLOSE => [
            'target' => Url::to(['menu/index', 'menutype' => $model->id])
        ],
        Toolbar::BUTTON_SAVE_NEW => [
            'visible' => $accessControl->canCreateMenuType(),
            'target' => Url::to(['menu-type/create'])
        ],
        Toolbar::BUTTON_CANCEL => [
            'url' => Url::to(['menu/index', 'menutype' => $model->id]),
            'style' => 'gray'
        ],
    ],
]);
?>
<div class="menu-type-update">
    
    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
        'accessControl' => $accessControl,
    ]) ?>

</div>
