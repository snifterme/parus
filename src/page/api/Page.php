<?php

namespace rokorolov\parus\page\api;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\admin\base\BaseApi;
use Yii;

/**
 * Page
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Page extends BaseApi
{
    const WITH_AUTHOR = 'author';
    
    public $options = [
        'id' => null,
        'alias' => null,
        'page_status' => Status::STATUS_PUBLISHED,
        'language' => null,
        'author' => null,
        'group_by' => null,
        'limit' => null,
        'offset' => null,
        'order' => 'id',
        'home' => null,
        'with' => [],
        'where' => null,
    ];
    
    public function getPageBy($key, $value, $options = [])
    {
        $this->options[$key] = $value;
        
        return $this->getPage($options);
    }
    
    public function getHomePage($options = [])
    {
        $this->options['home'] = Settings::homePageYesSign();
        $this->options['limit'] = 1;
        
        return $this->getPage($options);
    }
    
    public function getPage($options = [])
    {
        $options = array_replace($this->options, $options);
        $with = $this->prepareRelations($options['with']);

        $page = Yii::createObject('rokorolov\parus\page\repositories\PageReadRepository')
            ->andFilterWhere(['and',
                ['in', 'p.id', $options['id']],
                ['in', 'p.slug', $options['alias']],
                ['in', 'p.status', $options['page_status']],
                ['in', 'p.created_by', $options['author']],
                ['in', 'p.language', $options['language']],
                ['p.home' => $options['home']]])
            ->orderBy('p.' . $options['order'])
            ->limit($options['limit']);
        
        !is_null($options['where']) && $page->where($options['where']);
        !is_null($options['group_by']) && $page->groupBy('p.' . $options['group_by']);
        !is_null($options['offset']) && $page->offset($options['offset']);
        
        $relations = [];
        
        if (isset($with[self::WITH_AUTHOR])) {
            array_push($relations, self::WITH_AUTHOR);
        }
        
        !empty($relations) && $page->with($relations);
        
        if (null === $options['home'] && (is_array($options['id']) || is_array($options['alias']) || empty($options['id']) && empty($options['alias']))) {
            if (empty($page = $page->findAll())) {
                return [];
            }
        } else {
            if (null === $page = $page->findOne()) {
                return null;
            }
        }

        return $page;
    }
}
