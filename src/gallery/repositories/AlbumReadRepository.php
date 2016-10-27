<?php

namespace rokorolov\parus\gallery\repositories;

use rokorolov\parus\gallery\models\Album;
use rokorolov\parus\gallery\models\AlbumLang;
use rokorolov\parus\gallery\dto\AlbumDto;
use rokorolov\parus\gallery\dto\AlbumLangDto;
use rokorolov\parus\admin\base\BaseReadRepository;
use Yii;
use yii\db\Query;

/**
 * AlbumReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_ALBUM = 'a';
    const TABLE_SELECT_PREFIX_ALBUM_LANG = 'al';
    
    /**
     * Find by id
     *
     * @param int $id
     * @return
     */
    public function findById($id, array $with = [])
    {
        $this->applyRelations($with);
        
        return $this->findFirstBy('a.id', $id);
    }
    
    public function findTranslations($id = null)
    {
        $rows = (new Query())
            ->select('*')
            ->from(AlbumLang::tableName())
            ->andFilterWhere(['in', 'album_id', $id])
            ->all();
        
        $translations = [];
        foreach ($rows as $row) {
            array_push($translations, $this->toAlbumLangDto($row, false));
        }

        return $translations;
    }
    
    public function existsByAlbumAliase($attribute, $id = null)
    {
        $exist = (new Query())
            ->from(Album::tableName() . ' a')
            ->where(['album_aliase' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(Album::tableName() . ' a');
        }

        return $this->query;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toAlbumDto($data, $prefix);
    }
    
    protected function getRelations()
    {
        return [
            'translation' => self::RELATION_ONE,
            'translations' => self::RELATION_MANY,
            'photo.translations' => self::RELATION_MANY,
        ];
    }
    
    protected function resolveTranslation($query)
    {
        $query->addSelect($this->selectAlbumLangAttributesMap())
            ->leftJoin(AlbumLang::tableName() . ' al', 'al.album_id = a.id');
    }
    
    protected function populateTranslation($album, &$data)
    {
        $album->translation = new AlbumLangDto($data, self::TABLE_SELECT_PREFIX_ALBUM_LANG);
    }
    
    protected function populateTranslations($album)
    {
        $album->translations = $this->findTranslations($album->id);
    }
    
    public function selectAttributesMap()
    {
        return 'a.id AS a_id, a.status AS a_status, a.album_aliase AS a_album_aliase, a.created_at AS a_created_at, a.modified_at AS a_modified_at';
    }

    public function selectAlbumLangAttributesMap()
    {
        return 'al.album_id AS al_album_id, al.language AS al_language, al.name AS al_name, al.description AS al_description';
    }
    
    public function toAlbumDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_ALBUM : null;
        return new AlbumDto($data, $prefix);
    }

    public function toAlbumLangDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_ALBUM_LANG : null;
        return new AlbumLangDto($data, $prefix);
    }
}
