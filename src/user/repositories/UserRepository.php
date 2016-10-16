<?php

namespace rokorolov\parus\user\repositories;

use rokorolov\parus\admin\base\BaseRepository;
use rokorolov\parus\user\models\User;
use rokorolov\parus\user\models\Profile;
use Yii;

/**
 * UserRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserRepository extends BaseRepository
{
    /**
     *
     * @param type $attribute
     * @param array $with
     * @return type
     */
    public function findByUsernameOrEmail($attribute, array $with = [])
    {
        if (!filter_var($attribute, FILTER_VALIDATE_EMAIL) === false) {
            return $this->findFirstBy('email', $attribute, $with);
        } else {
            return $this->findFirstBy('username', $attribute, $with);
        }
    }

    /**
     *
     * @return type
     */
    public function makeUserCreateModel()
    {
        $model = $this->getModel();
        $model->populateRelation('profile', Yii::createObject(Profile::class));

        return $model;
    }

    /**
     *
     * @param type $model
     * @param type $validate
     * @return type
     */
    public function updateProfile($model, $validate = true)
    {
        return $model->save($validate);
    }

    /**
     *
     * @param type $model
     * @param type $validate
     * @return type
     */
    public function addProfile($model, $validate = true)
    {
        return $model->save($validate);
    }

    /**
     *
     * @param type $attribute
     * @param type $id
     * @return type
     */
    public function existsByUsername($attribute, $id = null)
    {
        $exist = $this->withTrashed()
            ->where(['username' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }

    /**
     *
     * @param type $attribute
     * @param type $id
     * @return type
     */
    public function existsByEmail($attribute, $id = null)
    {
        $exist = $this->withTrashed()
            ->where(['email' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }

    /**
     *
     * @return type
     */
    public function findAllWithTrashed()
    {
        return $this->withTrashed()->all();
    }

    /**
     *
     * @return type
     */
    private function withTrashed()
    {
        return $this->getModel()->withTrashed();
    }

    /**
     *
     * @return type
     */
    public function model()
    {
        return User::className();
    }
}
