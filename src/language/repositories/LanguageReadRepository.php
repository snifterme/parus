<?php

namespace rokorolov\parus\language\repositories;

use rokorolov\parus\language\models\Language;
use rokorolov\parus\language\dto\LanguageDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use rokorolov\parus\admin\contracts\HasPresenter;	
use yii\db\Query;

/**
 * LanguageReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguageReadRepository extends BaseReadRepository implements HasPresenter
{
    const TABLE_SELECT_PREFIX_LANGUAGE = 'l';
    
    /**
     * Find by id
     *
     * @param int $id
     * @return 
     */
    public function findById($id, array $with = [])
    {
        $this->applyRelations($with);
        
        return $this->findFirstBy('l.id', $id);
    }
    
    public function existsByLangCode($attribute, $id = null)
    {
        $exist = (new Query())
                ->from(Language::tableName())
                ->where(['lang_code' => $attribute])
                ->andFilterWhere(['!=', 'id', $id])
                ->exists();
        
        return $exist;
    }

    public function getLanguagesAsArray()
    {
        $rows = $this->make()
            ->select('*')
            ->indexBy('lang_code')
            ->orderBy('order')
            ->all();
        
        $this->reset();
        
        return $rows;
    }
    
    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(Language::tableName() . ' l');
        }

        return $this->query;
    }
    
    public function presenter()
    {
        return 'rokorolov\parus\language\presenters\LanguagePresenter';
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toLanguageDto($data, $prefix);
    }
    
    public function selectAttributesMap()
    {
        return 'l.id AS l_id, l.title AS l_title, l.status AS l_status, l.order AS l_order, l.lang_code AS l_lang_code, l.image AS l_image,'
        . ' l.date_format AS l_date_format, l.date_time_format AS l_date_time_format, l.created_at AS l_created_at, l.modified_at AS l_modified_at';
    }

    public function toLanguageDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_LANGUAGE : null;
        return new LanguageDto($data, $prefix);
    }
}
