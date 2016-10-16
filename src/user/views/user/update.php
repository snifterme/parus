<?php

use rokorolov\helpers\Html;
use rokorolov\parus\user\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;

/**
 * @var yii\web\View $this
 * @var rokorolov\parus\user\models\User $model
 */

$this->params['headerIcon'] = Html::icon('user');
$this->title = Module::t('user', 'Edit Profile') . ': ' . $model->username;
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE,
        Toolbar::BUTTON_SAVE_CLOSE,
        Toolbar::BUTTON_SAVE_NEW => ['visible' => $accessControl->canCreateUser()],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ]
]);
?>

<div class="user-update">
    <?= $this->render('_update-form', [
        'model' => $model,
        'accessControl' => $accessControl,
        'toolbar' => $toolbar,
    ]) ?>
</div>