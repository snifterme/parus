<?php

use rokorolov\parus\filemanager\Module;
use rokorolov\helpers\Html;

$this->params['headerIcon'] = Html::icon('cloud-upload');
$this->title = Module::t('filemanager', 'File Manager');
?>


<?= rokorolov\parus\filemanager\widgets\FileManager::widget(); ?>

