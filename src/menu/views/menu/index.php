<?php

use rokorolov\parus\menu\Module;
use rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn;
use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['headerIcon'] = Html::icon('bars');
$this->title = Module::t('menu', 'Menu Manager');
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_NEW => [
            'url' => $searchModel->menutype === null ? ['index'] : ['create', 'menutype' => $searchModel->menutype->id],
            'enable' => $accessControl->canCreateMenu() && $searchModel->menutype !== null
        ],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="menu-index">
    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default panel-list">
                <div class="panel-title"><?= Module::t('menu', 'Menu Types') ?></div>
                <ul class="list-group side-nav">
                    <?php foreach ($searchModel->getMenuTypes() as $type) : ?>
                        <li class="list-group-item <?= $type->id == $searchModel->menutype->id ? 'ro-active' : '' ?>">
                            <ol class="side-subnav">
                                <li>
                                    <?= $accessControl->canUpdateMenuType() ? Html::a(Html::icon('pencil text-success'), ['menu-type/update', 'id' => $type->id]) : '' ?>
                                </li>
                                <li>
                                    <?= $accessControl->canDeleteMenuType() ? Html::a(Html::icon('times text-danger'), ['menu-type/delete', 'id' => $type->id], [
                                        'data-confirm' => Module::t('menu', 'Are you sure you want to delete this item?'),
                                        'data-method' => 'post'
                                    ]) : ''; ?>
                                </li>
                            </ol>
                            <?= Html::a($type->title, ['index', 'menutype' => $type->id]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="form-group">
                <?php
                    if ($accessControl->canCreateMenuType()) {
                        echo Html::a(Module::t('menu', 'Add Menu'), ['menu-type/create'], ['class' => "btn btn-info btn-sm"]);
                    } else {
                        echo Html::button(Module::t('menu', 'Add Menu'), ['class' => "btn btn-info btn-sm disabled"]);
                    }
                ?>
            </div>
        </div>
        
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php Pjax::begin([
                        'id' => 'pjax-container',
                    ]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'id' => $this->context->id . '-grid',
                        'options' => ['class' => 'grid-view table-responsive'],
                        'columns' => [

                            [
                                'attribute' => 'id',
                                'headerOptions' => ['class' => 'grid-head-id text-center sort-numerical'],
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'attribute' => 'title',
                                'value' => function ($model) {
                                    return $model->title_nested_link;
                                },
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'grid-head-title'],
                            ],
                            [
                                'attribute' => 'link',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->link_to();
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'filter' => $searchModel->getStatusOptions(),
                                'content' => function($model, $key) use ($searchModel, $accessControl) {
                                    return StatusAction::widget([
                                        'key' => $key,
                                        'status' => $model->status,
                                        'buttons' => $searchModel->getStatusActions(),
                                        'pjaxContainer' => 'pjax-container',
                                        'enable' => $accessControl->canUpdateMenu(),
                                    ]);
                                },
                                'headerOptions' => ['class' => 'grid-head-status'],
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'visibleButtons' => [
                                    'update' => function ($model, $key, $index) use ($accessControl) {
                                        return $accessControl->canUpdateMenu();
                                    },
                                    'delete' => function ($model, $key, $index) use ($accessControl) {
                                        return $accessControl->canDeleteMenu($model);
                                    },
                                    'view' => function ($model, $key, $index) use ($accessControl) {
                                        return $accessControl->canViewMenu();
                                    }
                                ],
                                'headerOptions' => ['class' => 'grid-head-action'],
                                'contentOptions' => ['class' => 'text-center'],
                            ],

                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>