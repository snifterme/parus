<?php

namespace rokorolov\parus\menu\repositories;

use rokorolov\parus\menu\models\Menu;
use rokorolov\parus\admin\base\BaseRepository;
use Yii;

/**
 * MenuRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuRepository extends BaseRepository
{
    public function findChildren(Menu $menu)
    {
        return $menu->children()->all();
    }
    
    public function makeMenuCreateModel()
    {
        return $this->getModel();
    }
    
    public function removeWithChildren($model)
    {
        return $model->deleteWithChildren();
    }

    public function findAllWithTrashed()
    {
        return $this->withTrashed()->all();
    }

    private function withTrashed()
    {
        return $this->getModel()->withTrashed();
    }
    
    public function model()
    {
        return Menu::className();
    }
}
