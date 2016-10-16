<?php

namespace rokorolov\parus\blog\repositories;

use rokorolov\parus\blog\models\Category;
use rokorolov\parus\admin\base\BaseRepository;

/**
 * ARCategoryRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryRepository extends BaseRepository
{
    public function makeCategoryCreateModel()
    {
        return $this->getModel();
    }

    public function existsBySlug($attribute, $id = null)
    {
        $exist = $this->make()
            ->where(['slug' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    public function model()
    {
        return Category::className();
    }
}
