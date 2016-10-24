<?php

namespace rokorolov\parus\menu\api;

use rokorolov\parus\admin\helpers\UrlHelper;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use Yii;
use yii\caching\TagDependency;

/**
 * Menu
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Menu
{
    public static $navItemsMap;

    public static function navItems($menuAliase, $language = null, $cache = true)
    {
        return self::prepareNavItems(self::items($menuAliase, $language, $cache = true), 1);
    }
    
    public static function items($menuAliase, $language = null, $cache = true)
    {
        $cacheKey = static::class . ':' . $menuAliase . 'language:' . $language;
        if (!$cache || false === $items = Yii::$app->cache->get($cacheKey)) {
            $items = Yii::createObject('rokorolov\parus\menu\repositories\MenuReadRepository')->findAllMenuByAliaseAsArray($menuAliase, [
                'language' => $language,
                'status' => Status::STATUS_PUBLISHED
            ]);
            if (!empty($items)) {
                Yii::$app->cache->set(
                    $cacheKey,
                    $items,
                    86400,
                    new TagDependency([
                        'tags' => TagDependencyNamingHelper::getCommonTag(Settings::menuDependencyTagName())
                    ])
                );
            }
        }
        return $items;
    }

    public static function prepareNavItems($items, $parent)
    {
        $navItems = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parent) {
                $item['children'] = self::prepareNavItems($items, $item['id']);
                $url = UrlHelper::fromString($item['link']);
                $line = [
                    'label' => $item['title'],
                    'url' => $url,
                    'active' => Yii::$app->request->url === $url
                ];
                if (!empty($item['children'])) {
                    $line['items'] = $item['children'];
                }
                $navItems[] = $line;
            }
        }
        return $navItems;
    }
}
