<?php

namespace rokorolov\parus\gallery\repositories;

use rokorolov\parus\gallery\models\Photo;
use rokorolov\parus\admin\base\BaseRepository;
use Yii;

/**
 * PhotoRepository
 */
class PhotoRepository extends BaseRepository
{
    public function makePhotoCreateModel()
    {
        return $this->getModel();
    }

    public function makePhotoLangModel()
    {
        return Yii::createObject('rokorolov\parus\gallery\models\PhotoLang');
    }
    
    public function model()
    {
        return Photo::className();
    }
}
