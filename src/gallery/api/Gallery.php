<?php

namespace rokorolov\parus\gallery\api;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use Yii;

/**
 * Gallery
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Gallery
{
    const WITH_ALBUM_TRANSLATIONS = 'album.translations';
    const WITH_PHOTOS = 'photos';
    const WITH_PHOTOS_TRANSLATIONS = 'photos.translations';
    
    public $options = [
        'album_status' => Status::STATUS_PUBLISHED,
        'album_order' => 'id',
        'photo_with' => [],
        'photo_order' => 'order',
        'photo_status' => Status::STATUS_PUBLISHED,
        'with' => [],
    ];
    
    public function getById($id, $options = [])
    {
        return $this->getAlbum('a.id', $id, $options);
    }
    
    public function getByAlias($alias, $options = [])
    {
        return $this->getAlbum('a.album_aliase', $alias, $options);
    }
    
    protected function getAlbum($key, $value, $options)
    {
        $options = array_replace($this->options, $options);
        
        $album = Yii::createObject('rokorolov\parus\gallery\repositories\AlbumReadRepository')
            ->andFilterWhere(['in', 'a.status', $options['album_status']])
            ->andFilterWhere(['in', $key, $value])
            ->orderBy($options['album_order']);
        
        if (in_array(self::WITH_ALBUM_TRANSLATIONS, $options['with'])) {
            $album->with(['translations']);
        }
            
        if (is_array($value) || empty($value)) {
            if (empty($album = $album->findAll())) {
                return [];
            }
        } else {
            if (null === $album = $album->findOne()) {
                return null;
            }
        }
        
        if ($this->isPhotoRelation($options)) {
            $this->populatePhotoRelation($album, $options);
        }

        return $album;
    }
    
    protected function isPhotoRelation($options)
    {
        return in_array(self::WITH_PHOTOS, $options['with']) || in_array(self::WITH_PHOTOS_TRANSLATIONS, $options['with']);
    }
    
    protected function populatePhotoRelation($albums, $options)
    {
        !is_array($albums) && $albums = [$albums];
        
        foreach ($albums as $album) {
            $photoRepository = Yii::createObject('rokorolov\parus\gallery\repositories\PhotoReadRepository');
            $photos = $photoRepository
                ->andFilterWhere(['in', 'p.status', $options['photo_status']])
                ->orderBy($options['photo_order'])
                ->findManyBy('album_id', $album->id);

            if (in_array(self::WITH_PHOTOS_TRANSLATIONS, $options['with'])) {
                $photoRepository->eagerPopulateTranslations($photos);
            }

            $album->photos = $photos;
        }
    }
}
