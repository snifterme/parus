<?php

namespace rokorolov\parus\gallery\repositories;

use rokorolov\parus\gallery\models\Photo;
use rokorolov\parus\gallery\models\PhotoLang;
use rokorolov\parus\gallery\dto\PhotoDto;
use rokorolov\parus\gallery\dto\PhotoLangDto;	
use rokorolov\parus\admin\base\BaseReadRepository;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * PhotoReadRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoReadRepository extends BaseReadRepository
{
    const TABLE_SELECT_PREFIX_PHOTO = 'p';
    const TABLE_SELECT_PREFIX_PHOTO_LANG = 'pl';
    
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
    
    public function findByAlbumId($id, array $with = [])
    {
        $photos = $this->orderBy('order')->findManyBy('album_id', $id);
        
        if (in_array('translations', $with)) {
            $this->eagerPopulateTranslations($photos);
        }
        
        return $photos;
    }
    
    public function findTranslations($id = null)
    {
        $rows = (new Query())
            ->select('*')
            ->from(PhotoLang::tableName())
            ->andFilterWhere(['in', 'photo_id', $id])
            ->all();
        
        $translations = [];
        foreach ($rows as $row) {
            array_push($translations, $this->toPhotoLangDto($row, false));
        }

        return $translations;
    }

    public function make()
    {
        if (null === $this->query) {
            $this->query = (new Query())->from(Photo::tableName() . ' p');
        }

        return $this->query;
    }
    
    public function populate(&$data, $prefix = true)
    {
        return $this->toPhotoDto($data, $prefix);
    }
    
    protected function getRelations()
    {
        return [
            'translation' => self::RELATION_MANY,
            'translations' => self::RELATION_MANY,
        ];
    }
    
    protected function resolveTranslation($query)
    {
        $query->addSelect($this->selectPhotoLangAttributesMap())
            ->leftJoin(PhotoLang::tableName() . ' pl', 'pl.photo_id = p.id');
    }
    
    protected function populateTranslation($photo, &$data)
    {
        $photo->translation = new PhotoLangDto($data, self::TABLE_SELECT_PREFIX_PHOTO_LANG);
    }
    
    protected function populateTranslations($photo)
    {
        $photo->translations = $this->findTranslations($photo->id);
    }
    
    public function eagerPopulateTranslations($photos)
    {
        $ids = ArrayHelper::getColumn($photos, 'id');
        $translations = ArrayHelper::index($this->findTranslations($ids), null, 'photo_id');
        foreach ($photos as $photo) {
            if (isset($translations[$photo->id])) {
                $photo->translations = $translations[$photo->id];
            }
        }
    }
    
    public function selectAttributesMap()
    {
        return 'p.id AS p_id, p.status AS p_status, p.order AS p_order, p.album_id AS p_album_id, p.photo_name AS p_photo_name, p.photo_size AS p_photo_size,'
        . ' p.photo_extension AS p_photo_extension, p.photo_mime AS p_photo_mime, p.photo_path AS p_photo_path, p.created_at AS p_created_at, p.updated_at AS p_updated_at';
    }

    public function selectPhotoLangAttributesMap()
    {
        return 'pl.photo_id AS pl_photo_id, pl.caption AS pl_caption, pl.description AS pl_description, pl.language AS pl_language';
    }
    
    public function toPhotoDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_PHOTO : null;
        return new PhotoDto($data, $prefix);
    }
    
    public function toPhotoLangDto(&$data, $prefix = true)
    {
        $prefix = $prefix === true ? self::TABLE_SELECT_PREFIX_PHOTO_LANG : null;
        return new PhotoLangDto($data, $prefix);
    }
}
