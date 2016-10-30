<?php

namespace rokorolov\parus\page\api;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use rokorolov\parus\page\helpers\Settings;
use Yii;

/**
 * Page
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Page
{
    const WITH_CREATED_BY = 'createdBy';
    const WITH_MODIFIED_BY = 'modifiedBy';
    
    public $options = [
        'status' => Status::STATUS_PUBLISHED,
        'order' => 'id',
        'with' => [],
    ];
    
    public function get($options = [])
    {
        return $this->getPage('p.id', null, $options);
    }
    
    public function getById($id, $options = [])
    {
        return $this->getPage('p.id', $id, $options);
    }
    
    public function getByAlias($alias, $options = [])
    {
        return $this->getPage('p.slug', $alias, $options);
    }
    
    public function getHomePage($options = [])
    {
        return $this->getPage('p.home', Settings::homePageYesSign(), $options);
    }
    
    protected function getPage($key, $value, $options)
    {
        $options = array_replace($this->options, $options);
        
        $page = Yii::createObject('rokorolov\parus\page\repositories\PageReadRepository')
            ->andFilterWhere(['in', 'p.status', $options['status']])
            ->andFilterWhere(['in', $key, $value])
            ->orderBy('p.' . $options['order']);
        
        if (in_array(self::WITH_CREATED_BY, $options['with']) || in_array(self::WITH_MODIFIED_BY, $options['with'])) {
            $page->with($options['with']);
        }
        
        if (is_array($value) || empty($value)) {
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
