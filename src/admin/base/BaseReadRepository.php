<?php

namespace rokorolov\parus\admin\base;

use Yii;

/**
 * BaseReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class BaseReadRepository
{
    const RELATION_MANY = 'many';
    const RELATION_ONE = 'one';

    public $presenter;
    
    protected $query;
    protected $skipPresenter = false;
    protected $relations = [];
    protected $resolvedRelations = [];
    protected $populatedRelations = [];
    protected $relationNameMap = [];
    
    public function findFirstBy($key, $value)
    {
        $query = $this->make()
            ->addSelect($this->selectAttributesMap())
            ->andWhere([$key => $value]);

        return $this->parserResult($query->one());
    }

    public function findManyBy($key, $value)
    {
        $rows = $this->make()
            ->addSelect($this->selectAttributesMap())
            ->andWhere([$key => $value])
            ->all();

        $models = [];
        foreach ($rows as $row) {
            if ($model = $this->parserResult($row)) {
                array_push($models, $model);
            }
        }
        return $models;
    }

    public function findAll()
    {
        $rows = $this->make()
            ->addSelect($this->selectAttributesMap())
            ->all();

        $models = [];
        foreach ($rows as $row) {
            if ($model = $this->parserResult($row)) {
                array_push($models, $model);
            }
        }
        return $models;
    }

    public function findFirst($key, $value = null)
    {
        $query = $this->make()
            ->addSelect($this->selectAttributesMap())
            ->andFilterWhere([$key => $value]);

        return $this->parserResult($query->one());
    }

    public function findOne()
    {
        $query = $this->make()
            ->addSelect($this->selectAttributesMap());

        return $this->parserResult($query->one());
    }

    public function exists()
    {
        $query = $this->make();

        $this->reset();

        return $query->exists();
    }

    public function scalar()
    {
        $query = $this->make();

        $this->reset();

        return $query->scalar();
    }

    public function column()
    {
        $query = $this->make();

        $this->reset();

        return $query->column();
    }

    public function count()
    {
        $query = $this->make();

        $this->reset();

        return $query->count();
    }
    
    public function select($attributes)
    {
        $query = $this->make()->select($attributes);
        return $this;
    }
    
    public function addSelect($attributes)
    {
        $query = $this->make()->addSelect($attributes);
        return $this;
    }

    public function with(array $relations = [])
    {
        $this->applyRelations($relations);
        return $this;
    }

    public function where($conditions)
    {
        $this->make()->andWhere($conditions);
        return $this;
    }

    public function filterWhere($conditions)
    {
        $this->make()->filterWhere($conditions);
        return $this;
    }
    
    public function andFilterWhere($conditions)
    {
        $this->make()->andFilterWhere($conditions);
        return $this;
    }
    
    public function orFilterWhere($conditions)
    {
        $this->make()->orFilterWhere($conditions);
        return $this;
    }

    public function orderBy($order)
    {
        $this->make()->orderBy($order);
        return $this;
    }
    
    public function groupBy($group)
    {
        $this->make()->groupBy($group);
        return $this;
    }
    
    public function indexBy($indexBy)
    {
        $this->make()->indexBy($indexBy);
        return $this;
    }

    public function limit($limit)
    {
        $this->make()->limit($limit);
        return $this;
    }
    
    public function offset($offset)
    {
        $this->make()->offset($offset);
        return $this;
    }
    
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;
        return $this;
    }
    
    public function skipPresenter($status = true)
    {
        $this->skipPresenter = $status;
        return $this;
    }

    protected function reset()
    {
        $this->query = null;
    }

    protected function applyRelations($relations)
    {
        if (!empty($relations)) {
            $this->resolveRelations($this->make(), $relations);
        }
    }

    protected function parserResult($row)
    {
        $this->reset();
        
        if (!empty($row)) {
            $result = $this->populate($row);
            $this->populateRelations($result, $row);
            return $this->applyPresenter($result);
        }
        return null;
    }
    
    protected function applyPresenter($result)
    {
        if (!$this->skipPresenter && !is_null($presenter = $this->presenter)) {
            $result = Yii::createObject($presenter, [$result]);
        }
        return $result;
    }
    
    protected function resolveRelations($query, $relations)
    {
        if (empty($relations)) {
            return;
        }
        $this->relations = $relations;
        $registeredRelations = $this->getRelations();
        foreach ($relations as $relation) {
            if (isset($registeredRelations[$relation]) && $registeredRelations[$relation] === self::RELATION_ONE) {
                $resolver = 'resolve' . $this->resolveRelationName($relation);
                $this->$resolver($query);
                array_push($this->resolvedRelations, $relation);
            }
        }
    }

    protected function populateRelations($post, $data)
    {
        if (empty($relations = $this->relations)) {
            return;
        }
        $registeredRelations = $this->getRelations();
        foreach ($relations as $relation) {
            if (isset($registeredRelations[$relation])) {
                $populater = 'populate' . $this->resolveRelationName($relation);
                $this->{$populater}($post, $data);
                array_push($this->populatedRelations, $relation);
            }
        }
    }

    protected function resolveRelationName($name)
    {
        if (!isset($this->relationNameMap[$name])) {
            $relationName = $name;
            while (($pos = strpos($relationName, '.')) !== false) {
                $childName = substr($relationName, $pos + 1);
                $relationName = substr($relationName, 0, $pos);
                $relationName = $relationName . ucfirst($childName);
            }
            $this->relationNameMap[$name] = ucfirst($relationName);
        }

        return $this->relationNameMap[$name];
    }
}
