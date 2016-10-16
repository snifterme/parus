<?php

use rokorolov\parus\gallery\Module;
use rokorolov\parus\admin\theme\widgets\translatable\TranslatableSwithButton;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\helpers\Html;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$translations = $model->getTranslationVariations();
?>
<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active">
                <a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('gallery', 'Album') ?></a>
            </li>
            <li>
                <a href="#tab-meta" data-toggle="tab"><?= Html::icon('flag') ?> <span class="hidden-xs"><?= Module::t('gallery', 'Meta') ?></span></a>
            </li>
        </ul>

        <?php $form = ActiveForm::begin([
            'id' => $this->context->id . '-form',
            'options' => ['class' => Toolbar::FORM_SELECTOR]
        ]); ?>

        <div class="album-form tab-content-area">
            <div class="tab-content tab-content-form">

                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">

                    <?php foreach ($translations as $index => $translation): ?>
                        <div class="translatable-field lang-<?= $index ?>">
                            <?= $form->field($translation, "[{$index}]name", [
                                'inputOptions' => [
                                    'class' => 'form-control input-lg',
                                    'placeholder' => Module::t('gallery', 'Enter album name')
                                ]
                            ])->textInput(['maxlength' => true]); ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <?= $form->field($model, 'album_aliase')->textInput(['maxlength' => 128]) ?>

                    <?php foreach ($translations as $index => $translation): ?>
                        <div class="translatable-field lang-<?= $index ?>">
                            <?= $form->field($translation, "[{$index}]description")->textarea(['maxlength' => true]); ?>
                        </div>
                    <?php endforeach; ?>

                </div>
                <div id="tab-meta" class="tab-pane">
                    <div class="form-horizontal">
                        <?php
                            $formLayout = $form->layout;
                            $form->layout = 'horizontal';
                            $form->fieldConfig['horizontalCssClasses']['label'] = 'col-sm-3';
                            $form->fieldConfig['horizontalCssClasses']['wrapper'] = 'col-sm-8';
                        ?>
                        <?= $form->field($model, 'id', [
                            'inputTemplate' => '<div class="row"><div class="col-sm-4">{input}</div></div>',
                        ])->textInput(['readonly' => true]); ?>

                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'options' => [
                                'placeholder' => Module::t('gallery', 'Select a status') . ' ...',
                            ],
                            'pluginOptions' => ['minimumResultsForSearch' => -1],
                            'data' => $model->getStatusOptions(),
                        ]); ?>

                        <?php
                            $form->layout = $formLayout;
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-toolbar">
                <?= $toolbar ?>
            </div>
        </div>
    </div>
    <aside class="col-md-4">
        <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('gallery', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
                <p><?= Module::t('gallery', 'Current status') ?>: <?= $model->getCurrentStatus() ?></p>
                <div class="aside-change-language"><?= Module::t('gallery', 'Change language') ?>: <?= TranslatableSwithButton::widget([
                    'style' => Html::TYPE_INFO,
                ]) ?> </div>
                <p><?= Module::t('gallery', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
                <?php
                    if (!$model->isNewRecord && $accessControl->canDeleteAlbum($model)) {
                        echo Html::a(Html::icon('trash') . ' ' . Module::t('gallery', 'Delete Album'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-gray btn-hover-danger',
                            'data-confirm' => Module::t('gallery', 'Are you sure you want to delete this record?'),
                            'data-method' => 'post'
                        ]);
                    }
                ?>
            </div>
        </div>
        <?php if (!$model->isNewRecord) : ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?= Html::icon('info') . ' ' . Module::t('gallery', 'Publishing info') ?> </div>
                </div>
                <div class="panel-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped detail-view'],
                        'attributes' => [
                            [
                                'attribute' => 'id',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'modified_at',
                                'format' => 'raw',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
    </aside>
</div>
<?php ActiveForm::end(); ?>