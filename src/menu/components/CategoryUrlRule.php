<?php

namespace rokorolov\parus\menu\components;

use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use Yii;
use yii\web\UrlRuleInterface;
use yii\caching\TagDependency;

/**
 * This is the CategoryUrlRule.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryUrlRule implements UrlRuleInterface
{
    public static $map;
    public static $mapId;

    public $excludePath = 'admin';
    public $urlPath = 'c';
    public $includePathToUrl = true;

    protected $categoryReadRepository;

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'category/show' && isset($params['id'])) {
            $slug = $this->getSlugById($params['id']);
            if ($slug === false) {
                return 'category/show?id=' . $params['id'];
            }
            return $this->includePathToUrl ? $this->urlPath . '/' . $slug : $slug;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();

        if (0 !== strpos($pathInfo, $this->excludePath)) {
            $pathSplit = explode('/', $pathInfo);
            if ($this->includePathToUrl && 0 !== strpos($pathSplit[0], $this->urlPath)) {
                return false;
            }
            $slug = array_pop($pathSplit);
            if ($categoryId = $this->getIdBySlug($slug)) {
                return ['entry/index', ['id' => $categoryId]];
            }
        }
        return false;
    }

    public function getSlugById($id)
    {
        $slug = [];
        $category = $this->getCategoryById($id);
        if($category) {
            if ($category['depth'] > 1) {
                $parents = $this->getCategoryParents($category);
                foreach ($parents as $parent) {
                   $slug[] = $parent['slug'];
                }
            }
            $slug[] = $category['slug'];

            return implode('/', $slug);
        }
        return false;
    }

    public function getIdBySlug($slug)
    {
        $mapKey = $slug;
        if (!isset(static::$mapId[$mapKey])) {
            $cacheKey = static::class . ':' . $mapKey;

            if (false === $id = Yii::$app->cache->get($cacheKey)) {
                if ($id = $this->categoryReadRepository()->getIdByCategorySlug($slug)) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $id,
                        86400,
                        new TagDependency([
                            'tags' => [
                                TagDependencyNamingHelper::getObjectTag(Settings::categoryDependencyTagName(), $id)
                            ]
                        ])
                    );
                }
            }
            static::$mapId[$mapKey] = $id;
        }
        return static::$mapId[$mapKey];
    }

    public function getCategoryById($id)
    {
        $mapKey = $id;
        if (!isset(static::$map[$mapKey])) {
            $cacheKey = static::class . ':' . $mapKey;
            if (false === $category = Yii::$app->cache->get($cacheKey)) {
                $category = $this->categoryReadRepository()->findCategoryForUrlResolver($id);
                if ($category) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $category,
                        86400,
                        new TagDependency([
                            'tags' => [
                                TagDependencyNamingHelper::getCommonTag(Settings::categoryDependencyTagName())
                            ]
                        ])
                    );
                }
            }
            static::$map[$mapKey] = $category;
        }
        return static::$map[$mapKey];
    }

    public function getCategoryParents($category)
    {
        $cacheKey = static::class . ':parent:' . $category['id'];
        if (false === $parents = Yii::$app->cache->get($cacheKey)) {
            $parents = $this->categoryReadRepository()->findParentCategoriesForUrlResolver($category);
            if (!empty($parents)) {
                $dependencyTags = [];
                foreach ($parents as $item) {
                    $dependencyTags[] = TagDependencyNamingHelper::getObjectTag(Settings::categoryDependencyTagName(), $item['id']);
                }
                $dependencyTags[] = TagDependencyNamingHelper::getObjectTag(Settings::categoryDependencyTagName(), $category['id']);
                Yii::$app->cache->set(
                    $cacheKey,
                    $parents,
                    86400,
                    new TagDependency([
                        'tags' => $dependencyTags
                    ])
                );
            }
        }
        foreach ($parents as $item) {
            static::$map[$item['id']] = $item;
        }
        return $parents;
    }

    public function categoryReadRepository()
    {
        if (null === $this->categoryReadRepository) {
            $this->categoryReadRepository = Yii::createObject('rokorolov\parus\blog\repositories\CategoryReadRepository');
        }
        return $this->categoryReadRepository;
    }
}
