<?php

namespace rokorolov\parus\page\repositories;

use rokorolov\parus\page\models\Page;
use rokorolov\parus\page\dto\PageDto;
use rokorolov\parus\user\models\User;
use rokorolov\parus\user\models\Profile;
use rokorolov\parus\admin\base\BaseReadRepository;
use Yii;
use yii\db\Query;

/**
 * PageReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_PAGE = 'p';

    public $softDelete = true;
    public $softDeleteAttribute = 'deleted_at';
    
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
        
        return $this->findFirstBy('p.id', $id);
    }

    public function existsBySlug($attribute, $id = null)
    {
        $exist = (new Query())
            ->from(Page::tableName())
            ->where(['slug' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();
        
        return $exist;
    }
    
    public function findForLinkPagePicker()
    {
        return $this->make()
            ->select('p.id, p.title')
            ->all();
    }
    
    public function getSlugByPageId($id)
    {
        $slug = $this->make()
            ->select('p.slug')
            ->where(['p.id' => $id])
            ->scalar();
        
        $this->reset();
        
        return $slug;
    }
    
    public function getIdByPageSlug($slug)
    {
        $id = $this->make()
            ->select('p.id')
            ->where(['p.slug' => $slug])
            ->scalar();
        
        $this->reset();
        
        return $id;
    }
    
    public function make()
    {
        if (null === $this->query) {
            $query = (new Query())->from(Page::tableName() . ' p');
            
            if ($this->softDelete) {
                $query->andWhere(['p.' . $this->softDeleteAttribute => null]);
            }
            
            $this->query = $query;
        }

        return $this->query;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toPageDto($data, $prefix);
    }
    
    protected function getRelations()
    {
        return [
            'createdBy' => self::RELATION_ONE,
            'modifiedBy' => self::RELATION_ONE,
            'author' => self::RELATION_ONE,
        ];
    }
    
    protected function resolveCreatedBy($query)
    {
        if (!in_array('modifiedBy', $this->resolvedRelations)) {
            $query->addSelect($this->getUserReadRepository()->selectAttributesMap())
                ->leftJoin(User::tableName() . ' u', 'p.created_by = u.id');
        }
    }
    
    protected function resolveModifiedBy($query)
    {
        if (!in_array('createdBy', $this->resolvedRelations)) {
            $query->addSelect($this->getUserReadRepository()->selectAttributesMap())
                ->leftJoin(User::tableName() . ' u', 'p.modified_by = u.id');
        }
    }
    
    public function resolveAuthor($query)
    {
        $userRepository = $this->getUserReadRepository();
        $query->addSelect($userRepository->selectAttributesMap() . ', ' . $userRepository->selectProfileAttributesMap())
            ->leftJoin(User::tableName() . ' u', 'p.created_by = u.id')
            ->leftJoin(Profile::tableName() . ' up', 'up.user_id = u.id');
    }
    
    protected function populateCreatedBy($page, &$data)
    {
        if (!in_array('modifiedBy', $this->populatedRelations)) {
            $page->createdBy = $this->getUserReadRepository()->parserResult($data);
        } else {
            $page->createdBy = $this->getUserReadRepository()->findById($page->created_by);
        }
    }
    
    protected function populateModifiedBy($page, &$data)
    {
        if (!in_array('createdBy', $this->populatedRelations)) {
            $page->modifiedBy = $this->getUserReadRepository()->parserResult($data);
        } else {
            $page->modifiedBy = $this->getUserReadRepository()->findById($page->modified_by);
        }
    }
    
    protected function populateAuthor($post, &$data)
    {
        $post->author = $this->getUserReadRepository()->with(['profile'])->parserResult($data);
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
        return 'p.id AS p_id, p.status AS p_status, p.hits AS p_hits, p.created_by AS p_created_by,'
        . ' p.created_at AS p_created_at, p.modified_by AS p_modified_by, p.modified_at AS p_modified_at,'
        . ' p.home AS p_home, p.view AS p_view, p.version AS p_version, p.reference AS p_reference, p.deleted_at AS p_deleted_at, p.language AS p_language, p.title AS p_title,'
        . ' p.slug AS p_slug, p.content AS p_content, p.meta_title AS p_meta_title, p.meta_keywords AS p_meta_keywords,'
        . ' p.meta_description AS p_meta_description';
    }

    public function toPageDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_PAGE : null;
        return new PageDto($data, $prefix);
    }
}
