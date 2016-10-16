<?php

use rokorolov\parus\admin\theme\ThemeAsset;
use rokorolov\parus\admin\theme\widgets\sidenav\SideNav;
use rokorolov\parus\admin\theme\helpers\ThemeHelper as Theme;
use rokorolov\parus\admin\theme\widgets\bootstrapnotify\BootstrapNotify;
use rokorolov\parus\admin\theme\widgets\bootstrapnotify\BootstrapNotifyAsset;
use rokorolov\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$bundle = ThemeAsset::register($this);
BootstrapNotifyAsset::register($this);

$theme = new Theme();
$theme->registerTranslation();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="fixed-left">
<?php $this->beginBody() ?>

    <div id="wrapper" class="wrapper">
        
        <?= $theme->topNav() ?>

        <aside id="sidebar" class="sidebar sidebar-left">
            <?= SideNav::widget([
                'items' => $theme->sideNavItems(),
                'activateParents' => true,
                'encodeLabels' => false,
                'options' => [
                    'id' => 'side-menu-list',
                ],
            ]);?>
        </aside>

    <div class="main-content">
        <div class="content container-fluid">
            <div class="content-header">
                <h1><?= isset($this->params['headerIcon']) ? $this->params['headerIcon'] . ' ' . Html::encode($this->title) : Html::encode($this->title); ?></h1>
            </div>

            <?= BootstrapNotify::widget(); ?>

            <div class="page-content">
                <?= $content ?>
            </div>
        </div>
        <footer class="footer">
            <span>&copy;  <?= $theme->appName() . ' ' . date('Y') ?></span>
        </footer>
    </div>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>