<?php

use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\menu\Module;
use yii\helpers\Url;

$this->params['headerIcon'] = Html::icon('bars');
$this->title = Module::t('menu', 'Add new Menu Item');

$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_CREATE,
        Toolbar::BUTTON_SAVE_CLOSE => [
            'linkOptions' => ['class' => 'toolbar-save ' . Toolbar::TRIGGER_SELECTOR, 'data-task' => 'save_close', 'data-target' => Url::toRoute(['index', 'menutype' => $model->menu_type_id])],
        ],
        Toolbar::BUTTON_SAVE_NEW => [
            'linkOptions' => ['class' => 'toolbar-update ' . Toolbar::TRIGGER_SELECTOR, 'data-task' => 'update', 'data-target' => Url::toRoute(['create', 'menutype' => $model->menu_type_id])],
        ],
        Toolbar::BUTTON_CANCEL => [
            'url' => ['index', 'menutype' =>  $model->menu_type_id],
            'style' => 'gray'
        ],
    ]
]);
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
        'toolbar' => $toolbar,
    ]) ?>

</div>
