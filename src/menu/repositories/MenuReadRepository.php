<?php

namespace rokorolov\parus\menu\repositories;

use rokorolov\parus\menu\models\Menu;
use rokorolov\parus\menu\models\MenuType;
use rokorolov\parus\menu\dto\MenuDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use rokorolov\parus\admin\contracts\HasPresenter;
use Yii;
use yii\db\Query;

/**
 * MenuReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuReadRepository extends BaseReadRepository implements HasPresenter
{
    const TABLE_SELECT_PREFIX_MENU = 'm';
    
    protected $menuTypeReadRepository;
    
    /**
     * Find by id
     *
     * @param int $id
     * @return
     */
    public function findById($id, array $with = [])
    {
        $this->applyRelations($with);
        
        return $this->findFirstBy('m.id', $id);
    }
    
    public function findChildrenListAsArray($menutype, $exclLft = null, $exclRgt = null)
    {
        $query = $this->make()
            ->select('m.id, m.depth, m.title')
            ->andWhere(['menu_type_id' => $menutype])
            ->orderBy(['m.lft' => SORT_ASC]);
        
        if ($exclLft !== null && $exclRgt !== null) {
            $query->andWhere('m.lft < :exclLft OR m.rgt > :exclRgt', [':exclLft' => $exclLft, ':exclRgt' => $exclRgt]);
        }
        
        $this->reset();
        
        return $query->all();
    }
    
    public function findForOrderOptions($menutype, $depth = 1)
    {
        $rows = $this->make()
            ->select('m.id, m.title')
            ->andWhere(['menu_type_id' => $menutype, 'depth' => $depth])
            ->orderBy('m.lft')
            ->all();
        
        $this->reset();
        
        return $rows;
    }
    
    public function findAllMenuByAliaseAsArray($aliase, $conditions = [], $select = 'm.*')
    {
        $rows = $this->make()
            ->select($select)
            ->leftJoin(MenuType::tableName() . ' mt', 'm.menu_type_id = mt.id')
            ->andWhere(['menu_type_aliase' => $aliase])
            ->andFilterWhere($conditions)
            ->orderBy('m.lft')
            ->all();
        
        $this->reset();
        
        return $rows;
    }
    
    public function presenter()
    {
        return 'rokorolov\parus\menu\presenters\MenuPresenter';
    }
    
    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(Menu::tableName() . ' m');
        }

        return $this->query;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toMenuDto($data, $prefix);
    }

    protected function getRelations()
    {
        return [
            'menuType' => self::RELATION_ONE,
        ];
    }
    
    protected function resolveMenuType($query)
    {
        $query->addSelect($this->getMenuTypeReadRepository()->selectAttributesMap())
            ->leftJoin(MenuType::tableName() . ' mt', 'm.menu_type_id = mt.id');
    }
    
    protected function populateMenuType($menu, &$data)
    {
        $menu->menuType = $this->getMenuTypeReadRepository()->toMenuTypeDto($data);
    }

    protected function getMenuTypeReadRepository()
    {
        if ($this->menuTypeReadRepository === null) {
            $this->menuTypeReadRepository = Yii::createObject('rokorolov\parus\menu\repositories\MenuTypeReadRepository');
        }
        return $this->menuTypeReadRepository;
    }
    
    public function selectAttributesMap()
    {
        return 'm.id AS m_id, m.menu_type_id AS m_menu_type_id, m.link AS m_link, m.note AS m_note, m.parent_id AS m_parent_id, m.status AS m_status,'
        . 'm.language AS m_language, m.title AS m_title,  m.depth AS m_depth, m.lft AS m_lft, m.rgt AS m_rgt';
    }

    public function toMenuDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_MENU : null;
        return new MenuDto($data, $prefix);
    }
}
