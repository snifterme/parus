<?php

namespace rokorolov\parus\language\api;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use rokorolov\parus\admin\base\BaseApi;
use Yii;

/**
 * Language
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Language extends BaseApi
{
    const WITH_AUTHOR = 'author';
    
    public $options = [
        'id' => null,
        'alias' => null,
        'status' => Status::STATUS_PUBLISHED,
        'order' => 'order ASC',
        'where' => null,
    ];
    
    public function getLanguageBy($key, $value, $options = [])
    {
        $this->options[$key] = $value;
        
        return $this->getLanguage($options);
    }
    
    public function getLanguage($options = [])
    {
        $options = array_replace($this->options, $options);
        
        $language = Yii::createObject('rokorolov\parus\language\repositories\LanguageReadRepository')
            ->andFilterWhere(['and',
                ['in', 'l.id', $options['id']],
                ['in', 'l.lang_code', $options['alias']],
                ['in', 'l.status', $options['status']]])
            ->orderBy($options['order']);
        
        !is_null($options['where']) && $language->where($options['where']);
        
        !empty($relations) && $language->with($relations);
        
        if (is_array($options['id']) || is_array($options['alias']) || empty($options['id']) && empty($options['alias'])) {
            if (empty($language = $language->findAll())) {
                return [];
            }
        } else {
            if (null === $language = $language->findOne()) {
                return null;
            }
        }

        return $language;
    }
    
    protected function prepareRelations($with)
    {
        $relations = [];
        foreach($with as $key => $value) {
            if (is_array($value)) {
                $relations[$key] = $value;
            } else {
                $relations[$value] = [];
            }
        }
        return $relations;
    }
}
