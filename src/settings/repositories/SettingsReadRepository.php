<?php

namespace rokorolov\parus\settings\repositories;

use rokorolov\parus\settings\models\Settings;
use rokorolov\parus\settings\models\SettingsLang;
use rokorolov\parus\settings\dto\SettingsDto;
use rokorolov\parus\settings\dto\SettingsLangDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use yii\db\Query;

/**
 * SettingsReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_SETTINGS = 's';
    const TABLE_SELECT_PREFIX_SETTINGS_LANG = 'sl';

    public function findAllWithTranslation($language)
    {
        return $this->with(['translation'])->where(['sl.language' => $language])->orderBy('s.order')->findAll();
    }
    
    public function findTranslationsBySettingId($id)
    {
        $rows = (new Query())
            ->select('*')
            ->from(SettingsLang::tableName())
            ->andWhere(['settings_id' => $id])
            ->all();
        
        $translations = [];
        foreach ($rows as $row) {
            array_push($translations, $this->toSettingsLangDto($row, false));
        }
        return $translations;
    }
    
    public function findAllAsArray()
    {
        $rows = $this->make()
            ->select('*')
            ->all();
        
        $this->reset();
        
        return $rows;
    }
    
    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(Settings::tableName() . ' s');
        }

        return $this->query;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toSettingsDto($data, $prefix);
    }
    
    protected function getRelations()
    {
        return [
            'translations' => self::RELATION_MANY,
            'translation' => self::RELATION_ONE
        ];
    }
    
    protected function resolveTranslation($query)
    {
        $query->addSelect($this->selectSettingsLangAttributesMap())
            ->leftJoin(SettingsLang::tableName() . ' sl', 'sl.settings_id = s.id');
    }
    
    protected function populateTranslation($setting, &$data)
    {
        $setting->translation = $this->toSettingsLangDto($data);
    }
    
    protected function populateTranslations($setting)
    {
        $setting->translations = $this->findTranslationsBySettingId($setting->id);
    }
    
    public function selectAttributesMap()
    {
        return 's.id AS s_id, s.param AS s_param, s.value AS s_value, s.default AS s_default, s.type AS s_type, s.order AS s_order,'
        . ' s.created_at AS s_created_at, s.modified_at AS s_modified_at';
    }

    public function selectSettingsLangAttributesMap()
    {
        return 'sl.settings_id AS sl_settings_id, sl.language AS sl_language, sl.label AS sl_label';
    }

    public function toSettingsDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_SETTINGS : null;
        return new SettingsDto($data, $prefix);
    }
    
    public function toSettingsLangDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_SETTINGS_LANG : null;
        return new SettingsLangDto($data, $prefix);
    }
}
