<?php

namespace rokorolov\parus\gallery\api;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use rokorolov\parus\admin\base\BaseApi;
use Yii;

/**
 * Gallery
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Gallery extends BaseApi
{
    const WITH_TRANSLATION = 'translations';
    const WITH_PHOTO = 'photo';
    const WITH_PHOTO_TRANSLATION = 'photo.translation';
    
    public $options = [
        'id' => null,
        'alias' => null,
        'album_status' => Status::STATUS_PUBLISHED,
        'album_order' => 'id',
        'photo_order' => 'order',
        'photo_status' => Status::STATUS_PUBLISHED,
        'with' => [],
    ];
    
    public function getAlbumBy($key, $value, $options = [])
    {
        $this->options[$key] = $value;
        
        return $this->getAlbum($options);
    }
    
    public function getAlbum($options = [])
    {
        $options = array_replace($this->options, $options);
        $with = $this->prepareRelations($options['with']);
        
        $album = Yii::createObject('rokorolov\parus\gallery\repositories\AlbumReadRepository')
            ->andFilterWhere(['and',
                ['in', 'a.id', $options['id']],
                ['in', 'a.album_aliase', $options['alias']],
                ['in', 'a.status', $options['album_status']]])
            ->orderBy('a.' . $options['album_order']);
        
        if (isset($with[self::WITH_TRANSLATION])) {
            $album->with([self::WITH_TRANSLATION]);
        }
            
        $collection = false;
        
        if (is_array($options['id']) || is_array($options['alias']) || empty($options['id']) && empty($options['alias'])) {
            if (empty($album = $album->findAll())) {
                return [];
            }
            $collection = true;
        } else {
            if (null === $album = $album->findOne()) {
                return null;
            }
            $album = [$album];
        }
        
        if ($this->isPhotoRelation($with)) {
            $this->populatePhotoRelation($album, $with, $options);
        }

        return $collection ? $album : array_shift($album);
    }
    
    protected function isPhotoRelation($with)
    {
        return isset($with[self::WITH_PHOTO]) || isset($with[self::WITH_PHOTO_TRANSLATION]);
    }
    
    protected function populatePhotoRelation($albums, $with, $options)
    {
        foreach ($albums as $album) {
            $photoRepository = Yii::createObject('rokorolov\parus\gallery\repositories\PhotoReadRepository');
            $photos = $photoRepository
                ->andFilterWhere(['in', 'p.status', $options['photo_status']])
                ->orderBy('p.' . $options['photo_order'])
                ->findManyBy('album_id', $album->id);

            if (isset($with[self::WITH_PHOTO_TRANSLATION])) {
                $photoRepository->eagerPopulateTranslations($photos);
            }

            $album->photos = $photos;
        }
    }
}
