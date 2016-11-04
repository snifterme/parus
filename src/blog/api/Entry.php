<?php

namespace rokorolov\parus\blog\api;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Entry
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Entry
{
    const WITH_CATEGORY = 'category';
    const WITH_POST = 'post';
    const WITH_AUTHOR = 'author';
    const WITH_POST_COUNT = 'post_count';
    
    public $postOptions = [
        'id' => null,
        'alias' => null,
        'author' => null,
        'category' => null,
        'language' => null,
        'category_alias' => null,
        'post_status' => Status::STATUS_PUBLISHED,
        'order' => 'id',
        'group_by' => null,
        'limit' => null,
        'offset' => null,
        'category_status' => Status::STATUS_PUBLISHED,
        'check_category_status' => true,
        'with' => [],
        'where' => null,
    ];
    
    public $categoryOptions = [
        'id' => null,
        'alias' => null,
        'author' => null,
        'language' => null,
        'order' => 'lft',
        'group_by' => null,
        'limit' => null,
        'offset' => null,
        'category_status' => Status::STATUS_PUBLISHED,
        'post_group_limit' => null,
        'with_root' => false,
        'depth' => null,
        'with' => [],
        'where' => null,
    ];
    
    protected $postReadRepository;
    protected $categoryReadRepository;
    
    public function getPostBy($key, $value, $options = [])
    {
        $this->postOptions[$key] = $value;
        
        return $this->getPost($options);
    }
    
    public function getCategoryBy($key, $value, $options = [])
    {
        $this->categoryOptions[$key] = $value;
        
        return $this->getCategory($options);
    }
    
    public function getPost($options = [])
    {
        $options = array_replace($this->postOptions, $options);
        $with = $this->prepareRelations($options['with']);
        
        $post = $this->getPostReadRepository()
            ->andFilterWhere(['and',
                ['in', 'p.id', $options['id']],
                ['in', 'p.slug', $options['alias']],
                ['in', 'p.status', $options['post_status']],
                ['in', 'p.category_id', $options['category']],
                ['in', 'p.created_by', $options['author']],
                ['in', 'p.language', $options['language']],
                ['in', 'c.slug', $options['category_alias']]])
            ->orderBy('p.' . $options['order'])
            ->limit($options['limit']);
        
        !is_null($options['group_by']) && $post->groupBy('p.' . $options['group_by']);
        !is_null($options['offset']) && $post->offset($options['offset']);
        !is_null($options['where']) && $post->where($options['where']);
        
        $relations = [];
        
        if (isset($with[self::WITH_CATEGORY])) {
            $post->andFilterWhere(['in', 'c.status', $options['category_status']]);
            array_push($relations, self::WITH_CATEGORY);
        } elseif(null !== $options['category_alias'] || $options['check_category_status']) {
            $post->resolveCategory($post->make());
            $post->andFilterWhere(['in', 'c.status', $options['category_status']]);
        }
        
        if (isset($with[self::WITH_AUTHOR])) {
            array_push($relations, self::WITH_AUTHOR);
        }

        !empty($relations) && $post->with($relations);
            
        if (is_array($options['id']) || is_array($options['alias']) || empty($options['id']) && empty($options['alias'])) {
            if (empty($post = $post->findAll())) {
                return [];
            }
        } else {
            if (null === $post = $post->findOne()) {
                return null;
            }
        }

        return $post;
    }
    
    public function getCategory($options = [])
    {
        $options = array_replace($this->categoryOptions, $options);
        $with = $this->prepareRelations($options['with']);
        
        $category = $this->getCategoryReadRepository()
            ->andFilterWhere(['and',
                ['in', 'c.id', $options['id']],
                ['in', 'c.slug', $options['alias']],
                ['in', 'c.status', $options['category_status']],
                ['in', 'c.created_by', $options['author']],
                ['in', 'c.language', $options['language']],
                ['c.depth' => $options['depth']]
            ])
            ->orderBy('c.' . $options['order'])
            ->limit($options['limit']);
        
        !$options['with_root'] && $category->where(['>', 'c.lft', 1]);
        !is_null($options['group_by']) && $category->groupBy('c.' . $options['group_by']);
        !is_null($options['offset']) && $category->offset($options['offset']);
        !is_null($options['where']) && $category->where($options['where']);
        
        $relations = [];
        
        if (isset($with[self::WITH_AUTHOR])) {
            array_push($relations, self::WITH_AUTHOR);
        }
        
        if (isset($with[self::WITH_POST_COUNT])) {
            
            $postCount = (new \yii\db\Query)->select('COUNT(p.id)')
                ->from(['node' => 'category', 'parent' => 'category', 'p' => 'post'])
                ->where(['and',
                    ['between', 'node.lft', new \yii\db\Expression('parent.lft'), new \yii\db\Expression('parent.rgt')],
                    ['node.id' => new \yii\db\Expression('p.category_id')],
                    ['parent.id' => new \yii\db\Expression('c.id')],
                    ['in', 'p.status',  Status::STATUS_PUBLISHED]
                ])
                ->groupBy('parent.id')
                ->orderBy('node.lft');
            
            $category->addSelect(['c_post_count' => $postCount]);
        }
        
        !empty($relations) && $category->with($relations);

        $collection = false;
        
        if (is_array($options['id']) || is_array($options['alias']) || empty($options['id']) && empty($options['alias'])) {
            if (empty($category = $category->findAll())) {
                return [];
            }
            $collection = true;
        } else {
            if (null === $category = $category->findOne()) {
                return null;
            }
            $category = [$category];
        }

        if (isset($with[self::WITH_POST])) {

            $postOptions = array_replace($this->postOptions, $with[self::WITH_POST]);
            $categoryIds = ArrayHelper::getColumn($category, 'id');
            $postOptions['check_category_status'] = false;
                
            if (!$options['post_group_limit']) {
                $postOptions['category'] = $categoryIds;
                $post = $this->getPost($postOptions);
            } else {
                $postOptions['limit'] = $options['post_group_limit'];
                $post = $this->getGroupPost('category_id', $categoryIds, $postOptions);
            }
            
            $post = ArrayHelper::index($post, null, 'category_id');

            foreach ($category as $categoryItem) {
                if (isset($post[$categoryItem->id])) {
                    $categoryItem->posts = $post[$categoryItem->id];
                }
            }
        }

        return $collection ? $category : array_shift($category);
    }
    
    public function getCategoryChildrenIds($parent, $level = 1)
    {
        $options['where'] = ['and', ['>', 'c.lft', $parent->lft], ['<', 'c.rgt', $parent->rgt]];
        $depth = $level === 1 ? $parent->depth + 1 : null;
        
        return $this->getCategoryReadRepository()->make()
            ->select('id')
            ->where(['and',
                ['>', 'c.lft', $parent->lft],
                ['<', 'c.rgt', $parent->rgt],
                ['c.status' => Status::STATUS_PUBLISHED]
            ])
            ->andFilterWhere(['c.depth' => $depth])
            ->all();
    }
    
    public function bindParamArray($prefix, $values, &$bindArray)
    {
        $str = '';
        foreach($values as $index => $value){
            $str .= ':' . $prefix . $index . ',';
            $bindArray[$prefix . $index] = $value;
        }
        return rtrim($str, ',');     
    }
    
    public function getGroupPost($group, $groupIds, $options)
    {
        $bindValues = [':limit' => $options['limit']];
        $bindCategories = $this->bindParamArray($group, $groupIds, $bindValues);

        $sql = "SELECT * FROM 
                (select post.*, @rank := IF(@group=$group, @rank+1, 1) AS rank, @group := $group as grp
                FROM post, (select @rank := 0, @group := 0) AS vars
                ORDER BY $group
                ) AS category WHERE rank <= :limit AND $group IN ($bindCategories)";
        
        if (null !== $language = $options['language']) {
            $bindLanguages = $this->bindParamArray('language', (array)$language, $bindValues);
            $sql .= " AND language in ($bindLanguages)";
        }
        
        if (null !== $status = $options['post_status']) {
            $bindStatus = $this->bindParamArray('status', (array)$status, $bindValues);
            $sql .= " AND status in ($bindStatus)";
        }
        
        $order = $options['order'];
        
        $sql .= " order by $order;";

        $rows = Yii::$app->db->createCommand($sql)->bindValues($bindValues)->queryAll();
        
        $models = [];
        foreach ($rows as $row) {
            if ($model = $this->getPostReadRepository()->populate($row, false)) {
                array_push($models, $model);
            }
        }
        return $models;
    }
    
    protected function prepareRelations($with, $relations = [])
    {
        $with = (array)$with;
        foreach($with as $key => $value) {
            if (is_array($value)) {
                $relations[$key] = $value;
            } else {
                $relations[$value] = [];
            }
        }
        return $relations;
    }
    
    protected function getPostReadRepository()
    {
        if (null === $this->postReadRepository) {
            $this->postReadRepository = Yii::createObject('rokorolov\parus\blog\repositories\PostReadRepository');
        }
        return $this->postReadRepository;
    }
    
    protected function getCategoryReadRepository()
    {
        if (null === $this->categoryReadRepository) {
            $this->categoryReadRepository = Yii::createObject('rokorolov\parus\blog\repositories\CategoryReadRepository');
        }
        return $this->categoryReadRepository;
    }
}
