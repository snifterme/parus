<?php

use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\blog\Module;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\blog\models\Category */

$this->params['headerIcon'] = Html::icon('folder-open-o');
$this->title = Module::t('blog', 'Edit Category') . ': ' . $model->title;
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW => ['visible' => $accessControl->canCreateCategory()],
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
