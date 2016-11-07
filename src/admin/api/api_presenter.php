<?php

/**
 * Presenter Api
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */

function set_post_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\blog\repositories\PostReadRepository', ['presenter' => $presenter]);
}

function set_category_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\blog\repositories\CategoryReadRepository', ['presenter' => $presenter]);
}

function set_album_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\gallery\repositories\AlbumReadRepository', ['presenter' => $presenter]);
}

function set_photo_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\gallery\repositories\PhotoReadRepository', ['presenter' => $presenter]);
}

function set_page_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\page\repositories\PageReadRepository', ['presenter' => $presenter]);
}

function set_user_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\user\repositories\UserReadRepository', ['presenter' => $presenter]);
}

function set_language_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\language\repositories\LanguageReadRepository', ['presenter' => $presenter]);
}

function set_menu_type_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\menu\repositories\MenuTypeReadRepository', ['presenter' => $presenter]);
}

function set_menu_presenter($presenter)
{
    \Yii::$container->set('rokorolov\parus\menu\repositories\MenuReadRepository', ['presenter' => $presenter]);
}