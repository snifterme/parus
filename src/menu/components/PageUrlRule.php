<?php

namespace rokorolov\parus\menu\components;

use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use rokorolov\parus\page\helpers\Settings;
use Yii;
use yii\web\UrlRuleInterface;
use yii\caching\TagDependency;

/**
 * This is the PageUrlRule.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageUrlRule implements UrlRuleInterface
{
    public static $mapId;
    public static $mapSlug;

    public $adminPanelPath = 'admin';
    
    protected $pageReadRepository;

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'page/show' && isset($params['id'])) {
            if (false === $slug = $this->getSlugById($params['id'])) {
                return 'page/show?id=' . $params['id'];
            }
            return $slug !== '/' ? $slug : '';
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (strpos($pathInfo, '/') === false && strpos($pathInfo, $this->adminPanelPath) !== 0) {
            if ($pageId = $this->getIdBySlug($pathInfo)) {
                return ['page/view', ['id' => $pageId]];
            }
        }
        return false;
    }

    public function getSlugById($id)
    {
        $mapKey = $id;
        if (!isset(static::$mapSlug[$mapKey])) {
            $cacheKey = static::class . ':' . $mapKey;
            if (false === $slug = Yii::$app->cache->get($cacheKey)) {
                if ($slug = $this->pageReadRepository()->getSlugByPageId($id)) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $slug,
                        86400,
                        new TagDependency([
                            'tags' => [
                                TagDependencyNamingHelper::getObjectTag(Settings::pageDependencyTagName(), $id)
                            ]
                        ])
                    );
                }
            }
            static::$mapSlug[$mapKey] = $slug;
        }
        return static::$mapSlug[$mapKey];
    }

    public function getIdBySlug($slug)
    {
        $mapKey = $slug;
        if (!isset(static::$mapId[$mapKey])) {
            $cacheKey = static::class . ':' . $mapKey;

            if (false === $id = Yii::$app->cache->get($cacheKey)) {
                if ($id = $this->pageReadRepository()->getIdByPageSlug($slug)) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $id,
                        86400,
                        new TagDependency([
                            'tags' => [
                                TagDependencyNamingHelper::getObjectTag(Settings::pageDependencyTagName(), $id)
                            ]
                        ])
                    );
                }
            }
            static::$mapId[$mapKey] = $id;
        }
        return static::$mapId[$mapKey];
    }

    public function pageReadRepository()
    {
        if (null === $this->pageReadRepository) {
            $this->pageReadRepository = Yii::createObject('rokorolov\parus\page\repositories\PageReadRepository');
        }
        return $this->pageReadRepository;
    }
}
