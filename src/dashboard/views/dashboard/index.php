<?php

use rokorolov\parus\dashboard\Module;
use rokorolov\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 */

$this->params['headerIcon'] = Html::icon('tachometer');
$this->title = Module::t('dashboard', 'Dashboard');
?>
<div class="dashboard-index">
    
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-info-block no-border">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <?= Html::icon('bar-chart-o') . ' ' . Module::t('dashboard', 'Popular posts') ?>
                    </h5>
                </div>
                <ul class="list-group list-striped">
                    <?php foreach ($popularPosts as $popularPost) : ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-10">
                                    <?= Html::bsBadge(Html::encode($popularPost->hits), $popularPost->hits >= 10 ? Html::TYPE_INFO : Html::TYPE_DEFAULT); ?>
                                    <span class="strong ml5">
                                        <?= Html::a(Html::encode($popularPost->title), $accessControl->canUpdatePost($popularPost) ? ['/admin/blog/post/update', 'id' => $popularPost->id] : '#') ?>
                                    </span>
                                </div>
                                <div class="col-sm-2">
                                    <span class="pull-right text-muted fp85">
                                        <?= Html::encode(Yii::$app->formatter->asDate($popularPost->created_at)); ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-purple-light-block no-border">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <?= Html::icon('clock-o') . ' ' . Module::t('dashboard', 'Recently added posts') ?>
                    </h5>
                </div>
                <ul class="list-group list-striped">
                    <?php foreach ($recentlyAddedPosts as $recentlyPost) : ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-10">
                                    <?php
                                        $status = ArrayHelper::getValue($statusManager->getStatusActions(), $recentlyPost->status);
                                        echo Html::button(Html::icon($status['icon']), ['class' => 'disabled f10 btn-xs btn btn-' . $status['type']]);
                                    ?>
                                    <span class="strong ml5">
                                        <?= Html::a(Html::encode($recentlyPost->title), $accessControl->canUpdatePost($recentlyPost) ? ['/admin/blog/post/update', 'id' => $recentlyPost->id] : '#') ?>
                                    </span>
                                </div>
                                <div class="col-sm-2">
                                    <span class="pull-right text-muted fp85">
                                        <?= Html::encode(Yii::$app->formatter->asDate($recentlyPost->created_at)); ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
</div>