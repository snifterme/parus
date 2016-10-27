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
    public function findTranslationsForNewLanguage($language = null)
    {
        $languageModel = $this->makePhotoLangModel();
        
        return $languageModel->find()
            ->andFilterWhere(['language' => $language])
            ->groupBy('photo_id')
            ->asArray()
            ->all();
    }
    
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
