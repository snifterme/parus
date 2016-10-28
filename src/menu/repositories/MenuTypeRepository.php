<?php

namespace rokorolov\parus\menu\repositories;

use rokorolov\parus\menu\models\MenuType;
use rokorolov\parus\admin\base\BaseRepository;

/**
 * MenuTypeRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeRepository extends BaseRepository
{
    public function makeMenuTypeCreateModel()
    {
        return $this->getModel();
    }
    
    public function existsByMenuTypeAlias($attribute, $id = null)
    {
        $exist = $this->make()
            ->where(['menu_type_aliase' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    public function model()
    {
        return MenuType::className();
    }
}
