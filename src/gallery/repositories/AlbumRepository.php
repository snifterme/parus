<?php

namespace rokorolov\parus\gallery\repositories;

use rokorolov\parus\gallery\models\Album;
use rokorolov\parus\admin\base\BaseRepository;
use Yii;

/**
 * AlbumRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumRepository extends BaseRepository
{
    public function findTranslationsForNewLanguage($language = null)
    {
        $languageModel = $this->makeAlbumLangModel();
                
        return $languageModel->find()
            ->andFilterWhere(['language' => $language])
            ->groupBy('album_id')
            ->asArray()
            ->all();
    }
    
    public function makeAlbumCreateModel()
    {
        return $this->getModel();
    }
    
    public function makeAlbumLangModel()
    {
        return Yii::createObject('rokorolov\parus\gallery\models\AlbumLang');
    }
    
    public function existsByAlbumAliase($attribute, $id = null)
    {
        $exist = $this->make()
            ->where(['album_aliase' => $attribute])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    public function model()
    {
        return Album::className();
    }
}
