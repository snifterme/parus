<?php

/**
 * Presenter Api
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */

function cache_add($key, $data, $expire = 86400, $dependency = null)
{
    if (false === \Yii::$app->cache->get($key)) {
        cache_set($key, $data, $expire = 86400, $dependency);
    }
    
    return false;
}

function cache_set($key, $data, $expire = 86400, $dependency = null)
{
    if (null === $dependency) {
        return \Yii::$app->cache->set($key, $data, $expire);
    }
    
    return (new rokorolov\parus\admin\helpers\TagDependencyCacheHelper())->setCache($key, $data, $expire, $dependency);
}

function cache_exists($key)
{
    return \Yii::$app->cache->exists($key);
}

function cache_get($key)
{
    return \Yii::$app->cache->get($key);
}

function cache_delete($key)
{
    return \Yii::$app->cache->delete($key);
}

function cache_flush()
{
    return \Yii::$app->cache->flush();
}