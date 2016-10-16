<?php

namespace rokorolov\parus\page\repositories;

use rokorolov\parus\page\models\Page;
use rokorolov\parus\admin\base\BaseRepository;
use Yii;

/**
 * PageRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageRepository extends BaseRepository
{
    public function makePageCreateModel()
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
        return Page::className();
    }
}
