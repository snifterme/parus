<?php

namespace rokorolov\parus\menu\repositories;

use rokorolov\parus\menu\models\MenuType;
use rokorolov\parus\menu\models\Menu;
use rokorolov\parus\menu\dto\MenuTypeDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use Yii;
use yii\db\Query;

/**
 * UserReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_MENU_TYPE = 'mt';
    
    protected $menuReadRepository;

    /**
     * Find by id
     *
     * @param int $id
     * @return
     */
    public function findById($id, array $with = [])
    {
        $this->applyRelations($with);
        
        return $this->findFirstBy('mt.id', $id);
    }
    
    public function findAllTypesForMenu()
    {
        return $this->orderBy('mt.id')->findAll();
    }

    public function findAllForOptions()
    {
        $rows = $this->make()
            ->select('id, title')
            ->orderBy('mt.id')
            ->all();
        
        $this->reset();
        
        return $rows;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toMenuTypeDto($data, $prefix);
    }
    
    public function existsByMenuTypeAlias($attribute, $id = null)
    {
        $exist = (new Query())
            ->from(MenuType::tableName() . ' mt')
            ->where(['menu_type_alias' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    protected function getRelations()
    {
        return [
            'menu' => self::RELATION_MANY,
        ];
    }
    
    protected function populateMenu($menuType, &$data)
    {
        $menuType->menu = $this->getMenuReadRepository()->findManyBy('menu_type_id', $menuType->id);
    }
    
    protected function getMenuReadRepository()
    {
        if ($this->menuReadRepository === null) {
            $this->menuReadRepository = Yii::createObject('rokorolov\parus\menu\repositories\MenuReadRepository');
        }
        return $this->menuReadRepository;
    }
    
    public function selectAttributesMap()
    {
        return 'mt.id AS mt_id, mt.menu_type_alias AS mt_menu_type_alias, mt.title AS mt_title, mt.description AS mt_description';
    }
    
    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(MenuType::tableName() . ' mt');
        }

        return $this->query;
    }

    public function toMenuTypeDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_MENU_TYPE : null;
        return new MenuTypeDto($data, $prefix);
    }
}
