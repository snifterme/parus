<?php

use rokorolov\parus\admin\theme\widgets\toolbar\Toolbar;
use rokorolov\parus\gallery\helpers\Settings;
use kartik\file\FileInput;
use rokorolov\parus\gallery\Module;
use rokorolov\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model rokorolov\parus\gallery\models\Photo */

$this->params['headerIcon'] = Html::icon('pencil-square-o');
$this->title = Module::t('gallery', 'Update album photos') . ': ' . $album->translation->name;
$toolbar = Toolbar::widget([
    'buttons' => [
        Toolbar::BUTTON_UPDATE => [
            'enable' => $accessControl->canUpdatePhoto()
        ],
        Toolbar::BUTTON_SAVE_CLOSE => [
            'visible' => $accessControl->canUpdatePhoto(),
            'target' => Url::to(['album/index']),
        ],
        Toolbar::BUTTON_CANCEL => [
            'url' => Url::to(['album/index']),
        ],
    ],
]);

?>

<div class="upload-image-area">
    <?= FileInput::widget([
        'name' => 'imageFile',
        'language' => Settings::panelLanguage(),
        'id' => 'images-upload-widget',
        'disabled' => !$accessControl->canCreatePhoto(),
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['photo/create']),
            'uploadExtraData' => [
                'album' => $album->id
            ],
            'allowedFileExtensions' => $config['allowedExtensions'] ? $config['allowedExtensions'] : null,
            'allowedFileTypes' => $config['allowedFileTypes'] ? $config['allowedFileTypes'] : null,
            'minImageWidth' => $config['minImageWidth'] ? $config['minImageWidth'] : null,
            'minImageHeight' => $config['minImageHeight'] ? $config['minImageHeight'] : null,
            'maxImageWidth' => $config['maxImageWidth'] ? $config['maxImageWidth'] : null,
            'maxImageHeight' => $config['maxImageHeight'] ? $config['maxImageHeight'] : null,
            'maxFileSize' => $config['maxFileSize'],
            'maxFileCount' => $config['maxFileCount'],
        ]
    ]);?>
</div>

<div class="pull-right gallery-head-toolbar">
    <?= $toolbar ?>
</div>
<div class="clearfix"></div>
<div class="photo-update">
    <?= $this->render('_form', [
        'album' => $album,
        'accessControl' => $accessControl,
        'toolbar' => $toolbar,
    ]);?>
</div>

<?php
$js = "
    $('#images-upload-widget').on('fileuploaded', function(event, files, extra) {
         $.pjax.reload({container:'#pjax-imageList', timeout: 7000});
    });
";
$this->registerJs($js, \yii\web\View::POS_READY)
?>

