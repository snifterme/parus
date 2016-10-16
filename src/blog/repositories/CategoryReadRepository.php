<?php

namespace rokorolov\parus\blog\repositories;

use rokorolov\parus\blog\models\Category;
use rokorolov\parus\blog\dto\CategoryDto;
use rokorolov\parus\admin\contracts\HasPresenter;
use rokorolov\parus\user\repositories\UserReadRepository;
use rokorolov\parus\user\models\User;
use rokorolov\parus\admin\base\BaseReadRepository;
use Yii;
use yii\db\Query;

/**
 * UserReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryReadRepository extends BaseReadRepository implements HasPresenter
{
    const TABLE_SELECT_PREFIX_CATEGORY = 'c';
    
    private $userReadRepository;
    
    /**
     * Find by id
     *
     * @param int $id
     * @return
     */
    public function findById($id, array $with = [])
    {
        $this->applyRelations($with);
        
        return $this->findFirstBy('c.id', $id);
    }
    
    public function findChildrenListAsArray($lft = 1, $rgt = null, $exclLft = null, $exclRgt = null)
    {
        $query = $this->make()
            ->select('c.id, c.depth, c.title')
            ->andWhere('c.lft > :lft', [':lft' => $lft])
            ->andFilterWhere(['<', 'c.rgt', $rgt])
            ->orderBy(['c.lft' => SORT_ASC]);
        
        if ($exclLft !== null && $exclRgt !== null) {
            $query->andWhere('c.lft < :exclLft OR c.rgt > :exclRgt', [':exclLft' => $exclLft, ':exclRgt' => $exclRgt]);
        }
        
        $this->reset();
        
        return $query->all();
    }
    
    public function findChildrenIds($lft = 1, $rgt = null, $exclLft = null, $exclRgt = null)
    {
        $query = $this->make()
            ->select('c.id')
            ->andWhere('c.lft >= :lft', [':lft' => $lft])
            ->andFilterWhere(['<=', 'c.rgt', $rgt]);
        
        if ($exclLft !== null && $exclRgt !== null) {
            $query->andWhere('c.lft < :exclLft OR c.rgt > :exclRgt', [':exclLft' => $exclLft, ':exclRgt' => $exclRgt]);
        }
        
        $this->reset();
        
        return $query->all();
    }
    
    public function findForLinkCategoryPicker()
    {
        return $this->findChildrenListAsArray(1, null);
    }
    
    public function findCategoryForUrlResolver($id)
    {
        $row = $this->make()
            ->select('c.id, c.lft, c.rgt, c.depth, c.slug')
            ->where(['c.id' => $id])
            ->one();

        $this->reset();
        
        return $row;
    }
    
    public function findParentCategoriesForUrlResolver($category)
    {
        $rows = $this->make()
            ->select('c.id, c.lft, c.rgt, c.depth, c.slug')
            ->where('lft < :lft && rgt > :rgt && depth > :depth', [':lft' => $category['lft'], ':rgt' => $category['rgt'], ':depth' => 0])
            ->all();

        $this->reset();
        
        return $rows;
    }
    
    public function getIdByCategorySlug($slug)
    {
        $id = $this->make()
            ->select('c.id')
            ->where(['c.slug' => $slug])
            ->scalar();
        
        $this->reset();
        
        return $id;
    }
                
    public function existsBySlug($attribute, $id = null)
    {
        $exist = (new Query())
            ->from(Category::tableName())
            ->where(['slug' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();
        
        return $exist;
    }

    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(Category::tableName() . ' c');
        }
        return $this->query;
    }
    
    public function presenter()
    {
        return 'rokorolov\parus\blog\presenters\CategoryPresenter';
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toCategoryDto($data, $prefix);
    }
    
    protected function getRelations()
    {
        return [
            'createdBy' => self::RELATION_ONE,
            'modifiedBy' => self::RELATION_ONE,
        ];
    }
    
    protected function resolveCreatedBy($query)
    {
        if (!in_array('modifiedBy', $this->resolvedRelations)) {
            $query->addSelect($this->getUserReadRepository()->selectAttributesMap())
                ->leftJoin(User::tableName() . ' u', 'c.created_by = u.id');
        }
    }
    
    protected function resolveModifiedBy($query)
    {
        if (!in_array('createdBy', $this->resolvedRelations)) {
            $query->addSelect($this->getUserReadRepository()->selectAttributesMap())
                ->leftJoin(User::tableName() . ' u', 'c.modified_by = u.id');
        }
    }

    protected function populateCreatedBy($category, &$data)
    {
        if (!in_array('modifiedBy', $this->populatedRelations)) {
            $category->createdBy = UserReadRepository::toUserDto($data);
        } else {
            $category->createdBy = $this->getUserReadRepository()->findById($category->created_by);
        }
    }
    
    protected function populateModifiedBy($category, &$data)
    {
        if (!in_array('createdBy', $this->populatedRelations)) {
            $category->modifiedBy = UserReadRepository::toUserDto($data);
        } else {
            $category->modifiedBy = $this->getUserReadRepository()->findById($category->modified_by);
        }
    }
    
    protected function getUserReadRepository()
    {
        if ($this->userReadRepository === null) {
            $this->userReadRepository = Yii::createObject('rokorolov\parus\user\repositories\UserReadRepository');
        }
        return $this->userReadRepository;
    }

    public function selectAttributesMap()
    {
        return 'c.id AS c_id, c.parent_id AS c_parent_id, c.status AS c_status, c.image AS c_image, c.created_by AS c_created_by, c.created_at AS c_created_at,'
        . ' c.modified_by AS c_modified_by, c.modified_at AS c_modified_at, c.depth AS c_depth, c.lft AS c_lft, c.rgt AS c_rgt,'
        . 'c.language AS c_language, c.title AS c_title, c.slug AS c_slug, c.description AS c_description,'
        . ' c.meta_title AS c_meta_title, c.meta_keywords AS c_meta_keywords, c.meta_description AS c_meta_description';
    }
    
    public function toCategoryDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_CATEGORY : null;
        return new CategoryDto($data, $prefix);
    }
}
