<?php

namespace rokorolov\parus\admin\helpers;

use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use Yii;
use yii\caching\TagDependency;
use yii\base\InvalidParamException;

/**
 * TagDependencyCacheHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class TagDependencyCacheHelper
{
    const TAG_POST = 'post';
    const TAG_CATEGORY = 'category';
    const TAG_ALBUM = 'album';
    const TAG_PHOTO = 'photo';
    const TAG_LANGUAGE = 'language';
    const TAG_MENU = 'menu';
    const TAG_MENU_TYPE = 'menu_type';
    const TAG_PAGE = 'page';
    const TAG_SETTINGS = 'settings';
    const TAG_USER = 'user';
    const TAG_PROFILE = 'profile';
    
    public function setCache($key, $data, $expire = 86400, $dependency)
    {
        if (empty($dependency)) {
            throw new InvalidParamException('Dependency parameter must not be empty');
        }
        
        $dependencyTags = $this->prepareCommonTag($this->getDependencyTags($dependency));
        
        return Yii::$app->cache->set($key, $data, $expire, new TagDependency(['tags' => $dependencyTags]));
    }
    
    public function prepareCommonTag($tags)
    {
        $dependencyTags = [];
        
        foreach ($tags as $tag) {
            $dependencyTags[] = TagDependencyNamingHelper::getCommonTag($tag);
        }
        
        return $dependencyTags;
    }
    
    public function getDependencyTags($key = [])
    {
        $key = (array)$key;
        
        $tags = [
            self::TAG_POST => \rokorolov\parus\blog\helpers\Settings::postDependencyTagName(),
            self::TAG_CATEGORY => \rokorolov\parus\blog\helpers\Settings::categoryDependencyTagName(),
            self::TAG_ALBUM => \rokorolov\parus\gallery\helpers\Settings::albumDependencyTagName(),
            self::TAG_PHOTO => \rokorolov\parus\gallery\helpers\Settings::photoDependencyTagName(),
            self::TAG_LANGUAGE => \rokorolov\parus\language\helpers\Settings::languageDependencyTagName(),
            self::TAG_MENU => \rokorolov\parus\menu\helpers\Settings::menuDependencyTagName(),
            self::TAG_MENU_TYPE => \rokorolov\parus\menu\helpers\Settings::menuTypeDependencyTagName(),
            self::TAG_PAGE => \rokorolov\parus\page\helpers\Settings::pageDependencyTagName(),
            self::TAG_SETTINGS => \rokorolov\parus\settings\helpers\Settings::settingsDependencyTagName(),
            self::TAG_USER => \rokorolov\parus\user\helpers\Settings::userDependencyTagName(),
            self::TAG_PROFILE => \rokorolov\parus\user\helpers\Settings::profileDependencyTagName(),
        ];
        
        if (!empty($key)) {
            return array_intersect_key($tags, array_flip($key));
        }
        
        return $tags;
    }
    
    
}
