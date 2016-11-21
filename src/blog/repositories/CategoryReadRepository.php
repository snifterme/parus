<?php

namespace rokorolov\parus\blog\repositories;

use rokorolov\parus\blog\models\Category;
use rokorolov\parus\blog\dto\CategoryDto;
use rokorolov\parus\user\models\User;
use rokorolov\parus\user\models\Profile;
use rokorolov\parus\admin\base\BaseReadRepository;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * UserReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_CATEGORY = 'c';
    
    private $postReadRepository;
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
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toCategoryDto($data, $prefix);
    }
    
    protected function getRelations()
    {
        return [
            'createdBy' => self::RELATION_ONE,
            'updatedBy' => self::RELATION_ONE,
            'author' => self::RELATION_ONE,
        ];
    }
    
    public function resolveCreatedBy($query)
    {
        if (!in_array('updatedBy', $this->resolvedRelations)) {
            $query->addSelect($this->getUserReadRepository()->selectAttributesMap())
                ->leftJoin(User::tableName() . ' u', 'c.created_by = u.id');
        }
    }
    
    public function resolveUpdatedBy($query)
    {
        if (!in_array('createdBy', $this->resolvedRelations)) {
            $query->addSelect($this->getUserReadRepository()->selectAttributesMap())
                ->leftJoin(User::tableName() . ' u', 'c.updated_by = u.id');
        }
    }
    
    public function resolveAuthor($query)
    {
        $userRepository = $this->getUserReadRepository();
        $query->addSelect($userRepository->selectAttributesMap() . ', ' . $userRepository->selectProfileAttributesMap())
            ->leftJoin(User::tableName() . ' u', 'c.created_by = u.id')
            ->leftJoin(Profile::tableName() . ' up', 'up.user_id = u.id');
    }

    protected function populateCreatedBy($category, &$data)
    {
        if (!in_array('updatedBy', $this->populatedRelations)) {
            $category->createdBy = $this->getUserReadRepository()->parserResult($data);
        } else {
            $category->createdBy = $this->getUserReadRepository()->findById($category->created_by);
        }
    }
    
    protected function populateUpdatedBy($category, &$data)
    {
        if (!in_array('createdBy', $this->populatedRelations)) {
            $category->updatedBy = $this->getUserReadRepository()->parserResult($data);
        } else {
            $category->updatedBy = $this->getUserReadRepository()->findById($category->updated_by);
        }
    }
    
    protected function populateAuthor($category, &$data)
    {
        $category->author = $this->getUserReadRepository()->with(['profile'])->parserResult($data);
    }
    
    public function eagerPopulatePost($categories)
    {
        $ids = ArrayHelper::getColumn($categories, 'id');
        $post = ArrayHelper::index($this->getPostReadRepository()->where(['in', 'category_id', $ids])->findAll(), null, 'category_id');
        
        foreach ($categories as $category) {
            if (isset($post[$category->id])) {
                $category->posts = $post[$category->id];
            }
        }
    }
    
    protected function getUserReadRepository()
    {
        if ($this->userReadRepository === null) {
            $this->userReadRepository = Yii::createObject('rokorolov\parus\user\repositories\UserReadRepository');
        }
        return $this->userReadRepository;
    }
    
    protected function getPostReadRepository()
    {
        if ($this->postReadRepository === null) {
            $this->postReadRepository = Yii::createObject('rokorolov\parus\blog\repositories\PostReadRepository');
        }
        return $this->postReadRepository;
    }

    public function selectAttributesMap()
    {
        return 'c.id AS c_id, c.parent_id AS c_parent_id, c.status AS c_status, c.image AS c_image, c.created_by AS c_created_by, c.created_at AS c_created_at,'
        . ' c.updated_by AS c_updated_by, c.updated_at AS c_updated_at, c.depth AS c_depth, c.lft AS c_lft, c.rgt AS c_rgt,'
        . 'c.language AS c_language, c.title AS c_title, c.slug AS c_slug, c.description AS c_description,'
        . ' c.meta_title AS c_meta_title, c.meta_keywords AS c_meta_keywords, c.meta_description AS c_meta_description';
    }
    
    public function toCategoryDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_CATEGORY : null;
        return new CategoryDto($data, $prefix);
    }
}
