<?php

use rokorolov\parus\settings\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Config */

$this->params['headerIcon'] = Html::icon('wrench');
$this->title = Module::t('settings', 'Update Settings');
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE => [
            'enable' => $accessControl->canUpdateSettings()
        ],
    ]
]);
?>
<div class="config-update">
    
    <?= $this->render('_form', [
        'models' => $models,
        'toolbar' => $toolbar,
    ]) ?>
    
</div>
