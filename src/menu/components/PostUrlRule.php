<?php

namespace rokorolov\parus\menu\components;

use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use Yii;
use yii\web\UrlRuleInterface;
use yii\caching\TagDependency;

/**
 * This is the PostUrlRule.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostUrlRule extends BaseUrlRule
{
    private static $mapId;
    private static $mapSlug;

    public $route = 'post/show';

    protected $postReadRepository;

    public function getSlugById($id)
    {
        $mapKey = $id;
        if (!isset(static::$mapSlug[$mapKey])) {
            $cacheKey = static::class . ':' . $mapKey;
            if (false === $slug = Yii::$app->cache->get($cacheKey)) {
                $slug = $this->postReadRepository()->getSlugByPostId($id);
                if ($slug) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $slug,
                        86400,
                        new TagDependency([
                            'tags' => [
                                TagDependencyNamingHelper::getObjectTag(Settings::postDependencyTagName(), $id)
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
                $id = $this->postReadRepository()->getIdByPostSlug($slug);
                if ($id) {
                    Yii::$app->cache->set(
                        $cacheKey,
                        $id,
                        86400,
                        new TagDependency([
                            'tags' => [
                                TagDependencyNamingHelper::getObjectTag(Settings::postDependencyTagName(), $id)
                            ]
                        ])
                    );
                }
            }
            static::$mapId[$mapKey] = $id;
        }
        return static::$mapId[$mapKey];
    }

    public function postReadRepository()
    {
        if (null === $this->postReadRepository) {
            $this->postReadRepository = Yii::createObject('rokorolov\parus\blog\repositories\PostReadRepository');
        }
        return $this->postReadRepository;
    }
}
