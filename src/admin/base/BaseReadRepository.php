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

        $this->reset();

        return $this->parserResult($query->one());
    }

    public function findManyBy($key, $value)
    {
        $rows = $this->make()
            ->addSelect($this->selectAttributesMap())
            ->andWhere([$key => $value])
            ->all();

        $this->reset();

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

        $this->reset();

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

        $this->reset();

        return $this->parserResult($query->one());
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

    public function orderBy($column, $direction = SORT_ASC)
    {
        $this->make()->orderBy([$column => $direction]);
        return $this;
    }

    public function limit($limit)
    {
        $this->make()->limit($limit);
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
