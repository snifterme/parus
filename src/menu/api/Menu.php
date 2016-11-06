<?php

namespace rokorolov\parus\menu\api;

use rokorolov\parus\admin\helpers\UrlHelper;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use rokorolov\parus\admin\base\BaseApi;
use Yii;
use yii\caching\TagDependency;

/**
 * Menu
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Menu extends BaseApi
{
    public $options = [
        'id' => null,
        'alias' => null,
        'language' => null,
        'order' => 'lft ASC',
        'cache' => true,
    ];
    
    public function getMenuBy($key, $value, $options = [])
    {
        $this->options[$key] = $value;
        
        return $this->getMenu($options);
    }
    
    public function getNavBy($key, $value, $options = [])
    {
        $this->options[$key] = $value;
        
        return $this->prepareNavItems($this->getMenu($options)->menu);
    }
    
    protected function getMenu($options = [])
    {
        $menuType = null;
        $options = array_replace($this->options, $options);
        $cacheKey = static::class . ':id' . $options['id'] . ':alias' . $options['alias'] . 'language:' . $options['language'];
        
        if (false === $options['cache'] || false === $menuType = Yii::$app->cache->get($cacheKey)) {
            $menuType = Yii::createObject('rokorolov\parus\menu\repositories\MenuTypeReadRepository')
                ->andFilterWhere(['and',
                    ['in', 'mt.id', $options['id']],
                    ['in', 'mt.menu_type_aliase', $options['alias']]])
                ->findOne();

            if ($menuType) {
                $menuType->menu = Yii::createObject('rokorolov\parus\menu\repositories\MenuReadRepository')
                    ->andFilterWhere(['and',
                        ['m.status' => Status::STATUS_PUBLISHED],
                        ['in', 'm.language', $options['language']]])
                    ->orderBy('m.' . $options['order'])
                    ->findManyBy('menu_type_id', $menuType->id);

                if ($menuType) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $menuType,
                        86400,
                        new TagDependency([
                            'tags' => TagDependencyNamingHelper::getCommonTag(Settings::menuDependencyTagName())
                        ])
                    );
                }
            }
        }
        
        return $menuType;
    }
    
    protected function prepareNavItems($menuItems, $parent = 1)
    {
        $navItems = [];
        foreach ($menuItems as $menu) {
            if ((int)$menu->parent_id === (int)$parent) {
                $menu->children = self::prepareNavItems($menuItems, $menu->id);
                $url = UrlHelper::fromString($menu->link);
                $line = [
                    'label' => $menu->title,
                    'url' => $url,
                    'active' => Yii::$app->request->url === $url
                ];
                if (!empty($menu->children)) {
                    $line['items'] = $menu->children;
                }
                $navItems[] = $line;
            }
        }
        return $navItems;
    }
}
