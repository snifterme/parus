<?php

use rokorolov\parus\user\Module;
use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var rokorolov\parus\user\models\User $model
 */

$this->params['headerIcon'] = Html::icon('user');
$this->title = Module::t('user', 'View Profile') . ': ' . $model->username;
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE_CUSTOM => [
            'url' => ['update', 'id' => $model->id],
            'visible' => $accessControl->canUpdateUser(['author_id' => $model->id])
        ],
        Toolbar::BUTTON_DELETE => [
            'url' => ['delete', 'id' => $model->id],
            'visible' => $accessControl->canDeleteUser(['author_id' => $model->id])
        ],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="view view-user">
    <div class="tab-nav tab-view">
        <ul class="nav nav-pills">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('home') . ' ' . Module::t('user', 'Details') ?></a></li>
        </ul>
    </div>
    <div class="tab-content tab-content-view">
        <div id="tab-details" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"><?= Html::icon('info') . ' ' . Module::t('user', 'User information') ?> </h5></div>
                        <div class="panel-body">

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'username',
                                        'format' => 'raw',
                                        'value' => $model->username_link,
                                        'label' => $viewHelper->getAttributeLabel('username'),
                                    ],
                                    [
                                        'attribute' => 'email',
                                        'label' => $viewHelper->getAttributeLabel('email'),
                                        'format' => 'email'
                                    ],
                                    [
                                        'attribute' => 'last_login_on',
                                        'value' => $model->last_login_on_medium_with_relative,
                                        'format' => 'raw',
                                        'label' => $viewHelper->getAttributeLabel('last_login_on'),
                                    ],
                                    [
                                        'attribute' => 'last_login_ip',
                                        'label' => $viewHelper->getAttributeLabel('last_login_ip'),
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"> <?= Html::icon('pencil-square-o') . ' ' . Module::t('user', 'Publishing info') ?></h5></div>
                        <div class="panel-body">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'id',
                                        'label' => $viewHelper->getAttributeLabel('id'),
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'value' => $model->created_at_medium_with_relative,
                                        'format' => 'raw',
                                        'label' => $viewHelper->getAttributeLabel('created_at'),
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'value' => $model->updated_at_medium_with_relative,
                                        'format' => 'raw',
                                        'label' => $viewHelper->getAttributeLabel('updated_at'),
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
