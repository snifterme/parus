<?php

use rokorolov\parus\admin\theme\helpers\ThemeHelper as Theme;
use yii\helpers\Url;
use rokorolov\helpers\Html;
use yii\bootstrap\Dropdown;
?>

<nav role="navigation" class="top-nav fixed-top" id="header">
    <div class="top-nav-header">
        <a href="<?= Url::to(['/admin/dashboard/dashboard/index']) ?>" class="top-nav-logo">
            <span class="top-nav-logo-text-small"><?= $theme->appName()[0] ?></span>
            <span class="top-nav-logo-text"><?= $theme->appName() ?></span>
        </a>
    </div>
    <div class="top-nav-content">
        <button class="top-nav-btn-menu-mobile js-open-sidebar pull-left"> <?= Html::icon('bars'); ?> </button>
        <form role="search" class="top-nav-form hidden-xs pull-left">
            <div class="top-nav-form-group">
                <input type="text" class="top-nav-form-input form-control" placeholder="<?= Theme::t('theme', 'Search')?>">
            </div>
        </form>
        <div class="top-nav-items pull-right">
            <div class="top-nav-item top-nav-item-lang dropdown">
                <a href="#" data-toggle="dropdown" class="top-nav-item-link dropdown-toggle"><?= $theme->topNavCurrentLanguage()['label'] ?></a>
                <?= Dropdown::widget([
                    'items' =>  $theme->topNavLanguages(),
                    'encodeLabels' => false
                ]);?>
            </div>
            <div class="top-nav-item dropdown">
                <a href="#" data-toggle="dropdown" class="top-nav-item-link dropdown-toggle">
                    <?= Html::icon('home', ['class' => 'top-nav-item-icon f21']) . ' <span class="hidden-xs">' . Theme::t('theme', 'Quick Access') . '<strong></strong></span>' ?> <b class="caret"></b>
                </a>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => Html::icon('external-link') . ' ' . Theme::t('theme', 'Site'), 'url' => $theme->frontendUrl(), 'linkOptions' => ['class' => 'top-nav-item-sublink', 'target' => '_blank']],
                        ['label' => Html::icon('history') . ' ' . Theme::t('theme', 'Clear Cache'), 'url' => $theme->clearCacheUrl(), 'linkOptions' => ['class' => 'top-nav-item-sublink']],
                    ],
                ]);?>
            </div>
            <div class="top-nav-item dropdown">
                <a href="#" data-toggle="dropdown" class="top-nav-item-link dropdown-toggle">
                    <?= Html::icon('smile-o', ['class' => 'top-nav-item-icon f21']) . ' <span class="hidden-xs">' . Theme::t('theme', 'Hi') .  ', ' . '<strong>' . Yii::$app->user->identity->username . '</strong></span>' ?> <b class="caret"></b>
                </a>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'dropdown-menu-right'],
                    'items' => [
                        ['label' => Html::icon('wrench') . ' ' . Theme::t('theme', 'Edit My Profile'), 'url' => $theme->profileUpdateUrl(), 'linkOptions' => ['class' => 'top-nav-item-sublink']],
                        ['label' => Html::icon('sign-out') . ' ' . Theme::t('theme', 'Logout'), 'url' => $theme->logoutUrl(), 'linkOptions' => ['class' => 'top-nav-item-sublink', 'data-method' => 'post']],
                    ],
                ]);?>
            </div>
        </div>
    </div>
</nav>