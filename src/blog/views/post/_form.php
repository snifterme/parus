<?php

use rokorolov\parus\blog\Module;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\admin\theme\widgets\sluggable\SluggableButton;
use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\Redactor;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs nav-tabs-form">
            <li class="active"><a href="#tab-details" data-toggle="tab"><?= Html::icon('pencil') ?> <span class="hidden-xs"><?= Module::t('blog', 'Post') ?></span></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?= Html::icon('cloud-upload') ?> <span class="hidden-xs"><?= Module::t('blog', 'Media') ?></span></a></li>
            <li><a href="#tab-relations" data-toggle="tab"><?= Html::icon('cubes') ?> <span class="hidden-xs"><?= Module::t('blog', 'Relationship') ?></span></a></li>
            <li><a href="#tab-seo" data-toggle="tab"><?= Html::icon('signal') ?> <span class="hidden-xs"><?= Module::t('blog', 'Seo') ?></span></a></li>
            <li><a href="#tab-meta" data-toggle="tab"><?= Html::icon('flag') ?> <span class="hidden-xs"><?= Module::t('blog', 'Meta') ?></span></a></li>
        </ul>

        <div class="post-form tab-content-area">
            <?php $form = ActiveForm::begin([
                'id' => $this->context->id . '-form',
                'options' => ['enctype' => 'multipart/form-data', 'class' => Toolbar::FORM_SELECTOR . ' form-label-left']
            ]); ?>
            <div class="tab-content tab-content-form">

                <?= $form->errorSummary($model); ?>

                <div id="tab-details" class="tab-pane active">

                    <?= $form->field($model, 'title', [
                        'inputOptions' => [
                            'class' => 'form-control input-lg',
                            'placeholder' => Module::t('blog', 'Enter Post Title'),
                            'maxlength' => 128,
                        ]
                    ]); ?>

                    <?= $form->field($model, 'slug', [
                        'inputOptions' => ['title' => $model->slug],
                        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'
                                . SluggableButton::widget([
                                    'selectorFrom' => "postform-title",
                                    'selectorTo' => "postform-slug",
                                    'clickEvent' => 'generate-slug',
                                    'options' => [
                                        'class' => 'btn-info',
                                    ]
                                ])
                        . '</span></div>',
                    ])->textInput(['maxlength' => 128]); ?>

                    <?= $form->field($model, 'fulltext')->widget(Redactor::class); ?>

                    <?= $form->field($model, 'introtext')->widget(Redactor::class); ?>

                </div>
                <div id="tab-image" class="tab-pane">
                    <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/*'],
                        'language' => Settings::panelLanguage(),
                        'pluginOptions' => [
                            'initialPreview' => [
                                $model->getImageOriginal(),
                            ],
                            'initialPreviewAsData' => true,
                            'showUpload' => false,
                        ]
                    ])->label(false);?>
                </div>
                <div id="tab-relations" class="tab-pane">
                    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                        'data' => $model->getCategoryOptions(),
                    ]); ?>
                </div>
                <div id="tab-seo" class="tab-pane">
                    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]); ?>
                    <?= $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]); ?>
                    <?= $form->field($model, 'meta_description')->textarea(['maxlength' => true]); ?>
                </div>
                <div id="tab-meta" class="tab-pane">
                    <div class="form-horizontal">
                        <?php
                            $formLayout = $form->layout;
                            $form->layout = 'horizontal';
                            $form->fieldConfig['horizontalCssClasses']['label'] = 'col-sm-4';
                            $form->fieldConfig['horizontalCssClasses']['wrapper'] = 'col-sm-8';
                        ?>
                        <?= $form->field($model, 'id', [
                            'inputTemplate' => '<div class="row"><div class="col-sm-4">{input}</div></div>',
                        ])->textInput(['readonly' => true]); ?>

                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'options' => [
                                'placeholder' => Module::t('blog', 'Select a status') . ' ...',
                            ],
                            'pluginOptions' => ['minimumResultsForSearch' => -1],
                            'data' => $model->getStatusOptions(),
                        ]); ?>
                        
                        <?= $form->field($model, 'language')->widget(Select2::classname(), [
                            'data' => $model->getLanguageOptions(),
                        ]); ?>
                        
                        <?= $form->field($model, 'view')->textInput(['maxlength' => true]); ?>

                        <?= $form->field($model, 'reference')->textInput(['maxlength' => true]); ?>
                        
                        <?= $form->field($model, 'published_at', [
                            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">' . Html::icon('calendar') . '</span>{input}</div>'
                        ])->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => Module::t('blog', 'Enter Published date')],
                            'type' => DateTimePicker::TYPE_INPUT,
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'todayBtn' => true,
                            ]
                        ]); ?>

                        <?= $form->field($model, 'publish_up', [
                            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">' . Html::icon('eye') . '</span>{input}</div>'
                        ])->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => Module::t('blog', 'Enter Start Publishing date') . ' ...'],
                            'type' => DateTimePicker::TYPE_INPUT,
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'todayBtn' => true,
                            ],
                        ]); ?>

                        <?= $form->field($model, 'publish_down', [
                            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">' . Html::icon('eye-slash') . '</span>{input}</div>'
                        ])->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => Module::t('blog', 'Enter Finish Publishing date') . ' ...'],
                            'type' => DateTimePicker::TYPE_INPUT,
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'todayBtn' => true,
                            ]
                        ]); ?>

                        <?= $form->field($model, 'version')->textInput(['readonly' => true]); ?>
                        
                        <?php
                            $form->layout = $formLayout;
                        ?>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
        <div class="form-toolbar">
            <?= $toolbar ?>
            <p><?= Module::t('blog', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
        </div>
    </div>
    </div>
    <aside class="col-md-4">
        <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?= Html::icon('cog') . ' ' . Module::t('blog', 'Actions') ?> </div>
            </div>
            <div class="panel-body">
                <?= $toolbar ?>
                <p><?= Module::t('blog', 'Current status') ?>: <?= $model->getCurrentStatus() ?></p>
                <p><?= Module::t('blog', 'Saved on') . ': ' . $model->getSavedOn() ?></p>
                <?php
                    if (!$model->isNewRecord && $accessControl->canDeletePost($model)) {
                        echo Html::a(Html::icon('trash') . ' ' . Module::t('blog', 'Delete Post'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-gray btn-hover-danger',
                            'data-confirm' => Module::t('blog', 'Are you sure you want to delete this record?'),
                            'data-method' => 'post'
                        ]);
                    }
                ?>
            </div>
        </div>
        <?php if (!$model->isNewRecord) : ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?= Html::icon('info') . ' ' . Module::t('blog', 'Publishing info') ?> </div>
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
                                    'attribute' => 'created_by',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'updated_by',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format' => 'raw',
                                ],
                            ],
                        ]) ?>
                </div>
            </div>
        <?php endif; ?>
    </aside>
</div>
        
<?php

$canRemoveImage = json_encode(!$model->isNewRecord && !empty($model->getImageOriginal()));
$url = Url::to(['remove-intro-image', 'id' => $model->id]);

$this->registerJs("
    $('.fileinput-remove-button').on('click', function(event) {
        if ($canRemoveImage) {
            $.post('$url', function(data) {
                App.notifyAll(data.messages);
            }, 'json');
        }
    });
");