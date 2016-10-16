<?php

use rokorolov\parus\user\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;

/**
 * @var yii\web\View $this
 * @var rokorolov\parus\user\models\User $model
 */

$this->title = Module::t('user', 'Add User');
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

<div class="user-create">
    <?= $this->render('_create-form', [
        'model' => $model,
        'toolbar' => $toolbar,
    ]) ?>
</div>
