<?php

/**
 * Api
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */

function get_menu_by($key, $value, $options = [])
{
    return (new rokorolov\parus\menu\api\Menu())->getMenuBy($key, $value, $options);
}

function get_nav_by($key, $value, $options = [])
{
    return (new rokorolov\parus\menu\api\Menu())->getNavBy($key, $value, $options);
}

function get_album_by($key, $value, $options = [])
{
    return (new rokorolov\parus\gallery\api\Gallery())->getAlbumBy($key, $value, $options);
}

function get_album($options = [])
{
    return (new rokorolov\parus\gallery\api\Gallery())->getAlbum($options);
}

function get_post_by($key, $value, $options = [])
{
    return (new rokorolov\parus\blog\api\Entry())->getPostBy($key, $value, $options);
}

function get_post($options = [])
{
    return (new rokorolov\parus\blog\api\Entry())->getPost($options);
}

function get_category_by($key, $value, $options = [])
{
    return (new rokorolov\parus\blog\api\Entry())->getCategoryBy($key, $value, $options);
}

function get_category($options = [])
{
    return (new rokorolov\parus\blog\api\Entry())->getCategory($options);
}

function get_page_by($key, $value, $options = [])
{
    return (new rokorolov\parus\page\api\Page())->getPageBy($key, $value, $options);
}

function get_home_page($options = [])
{
    return (new rokorolov\parus\page\api\Page())->getHomePage($options);
}

function get_page($options = [])
{
    return (new rokorolov\parus\page\api\Page())->getPage($options);
}

function get_language_by($key, $value, $options = [])
{
    return (new rokorolov\parus\language\api\Language())->getLanguageBy($key, $value, $options);
}

function get_language($options = [])
{
    return (new rokorolov\parus\language\api\Language())->getLanguage($options);
}

function get_setting($key)
{
    return (new rokorolov\parus\settings\api\Settings())->getByKey($key);
}

function get_settings()
{
    return (new rokorolov\parus\settings\api\Settings())->getAll();
}

function get_user_by($key, $value, $options = [])
{
    return (new rokorolov\parus\user\api\User())->getUserBy($key, $value, $options);
}

function get_user($options = [])
{
    return (new rokorolov\parus\user\api\User())->getUser($options);
}