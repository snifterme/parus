<?php

namespace rokorolov\parus\menu\repositories;

use rokorolov\parus\menu\models\MenuType;
use rokorolov\parus\menu\dto\MenuTypeDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use rokorolov\parus\admin\contracts\HasPresenter;
use yii\db\Query;

/**
 * UserReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeReadRepository extends BaseReadRepository implements HasPresenter
{
    const TABLE_SELECT_PREFIX_MENU_TYPE = 'mt';

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
    
    public function existsByMenuTypeAliase($attribute, $id = null)
    {
        $exist = (new Query())
            ->from(MenuType::tableName() . ' mt')
            ->where(['menu_type_aliase' => $attribute])
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

    protected function populateMenu($menuType)
    {
        $menuType->menu = [];
    }

    public function selectAttributesMap()
    {
        return 'mt.id AS mt_id, mt.menu_type_aliase AS mt_menu_type_aliase, mt.title AS mt_title, mt.description AS mt_description';
    }
    
    public function presenter()
    {
        return 'rokorolov\parus\menu\presenters\MenuTypePresenter';
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
