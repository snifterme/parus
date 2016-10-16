<?php

namespace rokorolov\parus\admin\base;

use Yii;
use yii\db\ActiveRecordInterface;
use yii\base\InvalidConfigException;

/**
 * BaseRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Find by id
     *
     * @param int $id
     * @return yii\db\ActiveRecord
     */
    public function findById($id, array $with = [])
    {
        return $this->make($with)->andWhere(['id' => $id])->one();
    }

    /**
     * Find a single entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     * @return yii\db\ActiveRecord
     */
    public function findFirstBy($key, $value, array $with = array())
    {
        return $this->make($with)->andWhere([$key => $value])->one();
    }

    /**
     * Find many entities by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     * @return array yii\db\ActiveRecord
     */
    public function findManyBy($key, $value, array $with = array())
    {
        return $this->make($with)->andWhere([$key => $value])->all();
    }

    /**
     * 
     * @return array yii\db\ActiveRecord
     */
    public function findAll(array $with = array())
    {
        return $this->make($with)->all();
    }
    
    /**
     *
     * @param type $key
     * @param type $value
     * @param array $with
     * @return type
     */
    public function existBy($key, $value, array $with = array())
    {
        return $this->make($with)->andWhere([$key => $value])->exists();
    }

    /**
     *
     * @param type $model
     * @param type $validate
     * @return type
     */
    public function add($model, $validate = false)
    {
        return $model->save($validate);
    }
    
    /**
     *
     * @param type $model
     * @param type $validate
     * @return type
     */
    public function update($model, $validate = false)
    {
        return $model->save($validate);
    }

    /**
     *
     * @param type $model
     * @return type
     */
    public function remove($model)
    {
        return $model->delete();
    }

    /**
     *
     * @return type
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Reset Model
     */
    public function resetModel()
    {
        $this->makeModel();
    }
    
    /**
     *
     * @param array $with
     * @return type
     */
    protected function make(array $with = [])
    {
        return $this->model->find()->with($with);
    }
    
    /**
     *
     * @return type
     * @throws InvalidConfigException
     */
    protected function makeModel()
    {
        $model = Yii::createObject($this->model());

        if (!$model instanceof ActiveRecordInterface) {
            throw new InvalidConfigException("Class {$this->model()} must be an instance of yii\db\ActiveRecordInterface");
        }

        return $this->model = $model;
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();
}
