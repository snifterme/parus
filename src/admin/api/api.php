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

function update_post_counter($id, $count = 1)
{
    return (new \rokorolov\parus\blog\api\Entry())->updatePostCounter($id, $count);
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

function update_page_counter($id, $count = 1)
{
    return (new \rokorolov\parus\page\api\Page())->updatePageCounter($id, $count);
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