<?php

use rokorolov\parus\gallery\Module;
use rokorolov\parus\gallery\GalleryAsset;
use rokorolov\parus\admin\theme\widgets\translatable\TranslatableSwithButton;
use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\admin\theme\widgets\statusaction\StatusAction;
use rokorolov\parus\admin\theme\MagnificPopupAsset;
use rokorolov\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

GalleryAsset::register($this);
MagnificPopupAsset::register($this);

?>

<ul class="nav nav-tabs nav-tabs-form">
    <li class="active">
        <a href="#tab-details" data-toggle="tab"><?= Html::icon('camera') ?> <span class="hidden-xs"><?= Module::t('gallery', 'Photos') ?></a>
    </li>
    <?= TranslatableSwithButton::widget([
        'type' => TranslatableSwithButton::TYPE_TAB,
        'containerOptions' => ['class' => 'pull-right']
    ]) ?>
</ul>

<?php if (!$accessControl->canUpdatePhoto()) {
    echo '<fieldset disabled>';
} ?>

<?php $form = ActiveForm::begin([
    'id' => $this->context->id . '-form',
    'options' => ['class' => Toolbar::FORM_SELECTOR]
]); ?>

<div class="gallery-form tab-content-area">
    <div class="tab-content tab-content-form">

        <?= $form->errorSummary($album->photos); ?>

        <div id="tab-details" class="tab-pane active">

            <?php Pjax::begin([
                'id' => 'pjax-imageList',
            ]); ?>
            <div class="table-responsive">
                <table class="table table-hover album-images-grid">
                    <thead>
                        <tr class="nodrag nodrop">
                            <th><?= Module::t('gallery', 'Image') ?></th>
                            <th><?= Module::t('gallery', 'Caption') ?></th>
                            <th><?= Module::t('gallery', 'Description') ?></th>
                            <th style="width: 15%"><?= Module::t('gallery', 'Position') ?></th>
                            <th class="text-center"><?= Module::t('gallery', 'Status') ?></th>
                            <th class="rk-fixed-width-xs"></th>
                        </tr>
                    </thead>
                    <tbody id="imageList">
                        <?php foreach($album->photos as $photoIndex => $photo) : ?>
                        <?php $translations = $photo->getTranslationVariations(); ?>
                        <tr data-id="<?= $photo->id ?>">
                            <td class="text-center">
                                <a class="popup-image popup-image-default" href="<?= $photo->getImageOriginal() ?>">
                                    <?= Html::img($photo->getImageThumb(), ['class' => 'img-thumbnail', 'alt' => $photo->photo_name, 'title' => $photo->photo_name . ' id:' . $photo->id]); ?>
                                </a>
                            </td>
                            <td>
                                <?php foreach ($translations as $index => $language): ?>
                                    <div class="translatable-field lang-<?= $index ?>">
                                        <?= $form->field($photo->translate($index), "[{$photo->id}][{$index}]caption")->textInput(['maxlength' => true])->label(false); ?>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php foreach ($translations as $index => $language): ?>
                                    <div class="translatable-field lang-<?= $index ?>">
                                        <?= $form->field($photo->translate($index), "[{$photo->id}][{$index}]description")->textInput(['maxlength' => true])->label(false); ?>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                            <td class="text-center">
                                <div class="row js-order-move drag-drop">
                                    <div class="col-lg-1 col-sm-2 col-md-4 col-xs-3 col-xs-offset-2 text-muted">
                                        <?= Html::icon('arrows', ['aria-hidden' => 'true', 'class' => ' fa-lg']); ?>
                                    </div>
                                    <div class="col-sm-3 col-xs-5 nopadding">
                                        <?= Html::bsBadge($photo->order, Html::TYPE_INFO) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <?= StatusAction::widget([
                                    'key' => $photo->id,
                                    'status' => $photo->status,
                                    'buttons' => $photo->getStatusActions(),
                                    'pjaxContainer' => 'pjax-imageList',
                                    'enable' => $accessControl->canUpdatePhoto(),
                                ]); ?>
                            </td>
                            <td class="text-center">
                            <?php
                                if ($accessControl->canDeletePhoto()) {
                                    echo Html::a(Html::icon('trash', ['class' => 'text-danger fa-fw']),
                                        ['photo/delete?id=' . $photo->id],
                                        [
                                            'class' => 'btn btn-default btn-dange btn-xs js-data-post',
                                            'data-message' => Module::t('gallery', 'Are you sure you want to delete this record?'),
                                            'data-pjax' => 0,
                                            'data-container' => 'pjax-imageList'
                                        ]
                                    );
                                } else {
                                    echo Html::button(Html::icon('trash', ['class' => 'text-danger fa-fw']),
                                        [
                                            'class' => 'btn btn-default btn-dange btn-xs disabled',
                                        ]
                                    );
                                }
                            ?>
                            </td>
                        </tr>
                         <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
    <div class="form-toolbar">
        <?= $toolbar ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php if (!$accessControl->canUpdatePhoto()) {
    echo '</fieldset>';
} ?>

<?php
    $reorderUrl = Url::to(['photo/reorder']);
    $js = "
        var pjaxId = '#pjax-imageList';
        $(document).on('ready pjax:success', function() {
            Sortable.create(imageList, {
                handle: '.js-order-move',
                animation: 150,
                store: {
                    get: function (sortable) {
                        return [];
                    },
                    set: function (sortable) {
                        var order = sortable.toArray();
                        $.post('$reorderUrl', {order: order, _csrf : yii.getCsrfToken()}, function(data) {
                            if (typeof App.notifyAll === 'function') {
                                App.notifyAll(data.messages);
                            }
                            $.pjax.reload({container:pjaxId});
                        }, 'json');

                    }
                },
            });
            $('.popup-image').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
            });
        });
    ";
    $this->registerJs($js);
?>