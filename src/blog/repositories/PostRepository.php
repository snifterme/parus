<?php

namespace rokorolov\parus\blog\repositories;

use rokorolov\parus\blog\models\Post;
use rokorolov\parus\admin\base\BaseRepository;
use Yii;

/**
 * ARPostRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostRepository extends BaseRepository
{
    
    public function makePostCreateModel()
    {
        return $this->getModel();
    }

    public function findAllWithTrashed()
    {
        return $this->withTrashed()->all();
    }

    private function withTrashed()
    {
        return $this->getModel()->withTrashed();
    }

    public function existsBySlug($attribute, $id = null)
    {
        $exist = $this->withTrashed()
            ->where(['slug' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    public function model()
    {
        return Post::className();
    }
}
