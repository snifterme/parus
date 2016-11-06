<?php

/**
 * Api
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */

function get_menu_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\menu\api\Menu())->getMenuBy($key, $value, $options);
}

function get_nav_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\menu\api\Menu())->getNavBy($key, $value, $options);
}

function get_album_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\gallery\api\Gallery())->getAlbumBy($key, $value, $options);
}

function get_album($options = [])
{
    return (new \rokorolov\parus\gallery\api\Gallery())->getAlbum($options);
}

function get_post_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\blog\api\Entry())->getPostBy($key, $value, $options);
}

function get_post($options = [])
{
    return (new \rokorolov\parus\blog\api\Entry())->getPost($options);
}

function get_popular_post($limit = 10, $options = [])
{
    $options['limit'] = $limit;
    $options['order'] = 'hits DESC';
    
    return (new \rokorolov\parus\blog\api\Entry())->getPost($options);
}

function get_last_post($limit = 10, $options = [])
{
    $options['limit'] = $limit;
    $options['order'] = 'published_at DESC';
    
    return (new \rokorolov\parus\blog\api\Entry())->getPost($options);
}

function get_category_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\blog\api\Entry())->getCategoryBy($key, $value, $options);
}

function get_category($options = [])
{
    return (new \rokorolov\parus\blog\api\Entry())->getCategory($options);
}

function get_root_category($options = [])
{
    $options['with_root'] = true;
    $rootId = \rokorolov\parus\blog\helpers\Settings::categoryRootId();
    
    return (new \rokorolov\parus\blog\api\Entry())->getCategoryBy('id', $rootId, $options);
}

function get_default_category()
{
    $defaultId = \rokorolov\parus\blog\helpers\Settings::categoryDefaultId();
    
    return (new \rokorolov\parus\blog\api\Entry())->getCategoryBy('id', $defaultId);
}

function get_category_parent($category, $level = 1, $options = [])
{
    if ($level === 1) {
        return (new \rokorolov\parus\blog\api\Entry())->getCategoryBy('id', $category->parent_id);
    }
    $options['where'] = ['and', ['<', 'c.lft', $category->lft], ['>', 'c.rgt', $category->rgt]];
    
    return (new \rokorolov\parus\blog\api\Entry())->getCategory($options);
}

function get_category_parent_ids($category, $level = 1)
{
    return \yii\helpers\ArrayHelper::getColumn((new \rokorolov\parus\blog\api\Entry())->getCategoryParentIds($category, $level), 'id');
}

function get_category_children($parent, $level = 1, $options = [])
{
    $options['where'] = ['and', ['>', 'c.lft', $parent->lft], ['<', 'c.rgt', $parent->rgt]];
    $options['depth'] = $level === 1 ? $parent->depth + 1 : null;

    return (new \rokorolov\parus\blog\api\Entry())->getCategory($options);
}

function get_category_children_ids($parent, $level = 1, $options = [])
{
    return \yii\helpers\ArrayHelper::getColumn((new \rokorolov\parus\blog\api\Entry())->getCategoryChildrenIds($parent, $level, $options), 'id');
}

function get_page_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\page\api\Page())->getPageBy($key, $value, $options);
}

function get_home_page($options = [])
{
    return (new \rokorolov\parus\page\api\Page())->getHomePage($options);
}

function get_page($options = [])
{
    return (new \rokorolov\parus\page\api\Page())->getPage($options);
}

function get_language_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\language\api\Language())->getLanguageBy($key, $value, $options);
}

function get_language($options = [])
{
    return (new \rokorolov\parus\language\api\Language())->getLanguage($options);
}

function get_setting($key)
{
    return (new \rokorolov\parus\settings\api\Settings())->getByKey($key);
}

function get_settings(array $keys = [])
{
    return (new \rokorolov\parus\settings\api\Settings())->getAll($keys);
}

function get_user_by($key, $value, $options = [])
{
    return (new \rokorolov\parus\user\api\User())->getUserBy($key, $value, $options);
}

function get_user($options = [])
{
    return (new \rokorolov\parus\user\api\User())->getUser($options);
}

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