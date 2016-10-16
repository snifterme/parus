<?php

use rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn;
use rokorolov\helpers\Html;
use rokorolov\parus\user\Module;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var rokorolov\parus\user\models\search\UserSearch $searchModel
 */

$this->params['headerIcon'] = Html::icon('user');
$this->title = Module::t('user', 'User Manager');
?>

<?= Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_NEW => ['enable' => $accessControl->canCreateUser()],
    ],
    'options' => ['class' => 'pull-right']
]); ?>

<div class="clearfix"></div>

<div class="user-index">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php Pjax::begin([
                'id' => 'pjax-container',
            ]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id' => 'grid-' . $this->context->id,
                'options' => ['class' => 'grid-view table-responsive'],
                'columns' => [

                    [
                         'attribute' => 'id',
                         'headerOptions' => ['class' => 'grid-head-id text-center'],
                         'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'username',
                        'value' => function ($model, $key, $index) {
                            return $model->username_link();
                        }, 
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'grid-head-title'],
                    ],
                    'email:email',
                    [
                        'attribute' => 'created_at',
                        'value' => function($model) {
                            return $model->created_at_date();
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                                'clearBtn' => true,
                            ]
                        ]),
                        'headerOptions' => ['class' => 'grid-head-date text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'last_login_on',
                        'value' => function($model) {
                            return $model->last_login_on_relative();
                        },
                        'filter' => false,
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'visibleButtons' => [
                            'update' => function ($model, $key, $index) use ($accessControl) {
                                return $accessControl->canUpdateUser(['author_id' => $model->id]);
                            },
                            'delete' => function ($model, $key, $index) use ($accessControl) {
                                return $accessControl->canDeleteUser(['author_id' => $model->id]);
                            },
                            'view' => function ($model, $key, $index) use ($accessControl) {
                                return $accessControl->canViewUser();
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
