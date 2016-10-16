<?php

use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\helpers\Html;
use rokorolov\parus\menu\Module;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\menu\models\Menu */

$this->params['headerIcon'] = Html::icon('bars');
$this->title = Module::t('menu', 'View Menu Item') . ': ' . $model->title;
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE_CUSTOM => [
            'url' => ['update', 'id' => $model->id],
            'visible' => $accessControl->canUpdateMenu()
        ],
        Toolbar::BUTTON_DELETE => [
            'url' => ['delete', 'id' => $model->id],
            'visible' => $accessControl->canDeleteMenu($model)
        ],
        Toolbar::BUTTON_CANCEL => [
            'style' => 'gray'
        ],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="menu-view">

    <div class="tab-nav tab-view">
        <ul class="nav nav-pills">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('home') . ' ' . Module::t('menu', 'Details') ?></a></li>
        </ul>
    </div>
    
    <div class="tab-content tab-content-view">
        <div id="tab-details" class="tab-pane active">
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h5 class="panel-title"><?= Html::icon('info') . ' ' . Module::t('menu', 'Information') ?> </h5></div>
                        <div class="panel-body">
                            <?php Pjax::begin([
                                'id' => 'pjax-container',
                            ]); ?>

                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'attribute' => 'title',
                                        'format' => 'raw',
                                        'label' => $viewHelper->getAttributeLabel('title'),
                                        'value' => Html::a(Html::encode($model->title), ['update', 'id' => $model->id], ['data-pjax' => 0, 'class' => 'grid-title-link']),
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'label' => $viewHelper->getAttributeLabel('status'),
                                        'value' => StatusAction::widget([
                                            'key' => $model->id,
                                            'status' => $model->status,
                                            'buttons' => $viewHelper->getStatusActions(),
                                            'pjaxContainer' => 'pjax-container',
                                            'enable' => $accessControl->canUpdateMenu(),

                                        ]),
                                    ],
                                    [
                                        'attribute' => 'menu_type_id',
                                        'label' => $viewHelper->getAttributeLabel('menu_type_id'),
                                        'value' => $model->menuType->title,
                                    ],
                                    [
                                        'attribute' => 'link',
                                        'format' => 'raw',
                                        'label' => $viewHelper->getAttributeLabel('link'),
                                        'value' => $model->link_to()
                                    ],
                                    [
                                       'attribute' => 'note',
                                        'label' => $viewHelper->getAttributeLabel('note'),
                                    ]
                                ],
                            ]) ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
