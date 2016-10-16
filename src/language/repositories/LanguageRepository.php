<?php

namespace rokorolov\parus\language\repositories;

use rokorolov\parus\language\models\Language;
use rokorolov\parus\admin\base\BaseRepository;

/**
 * LanguageRepository
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguageRepository extends BaseRepository
{
    
    public function existsByLangCode($code, $id = null)
    {
        $exist = $this->model->find()
            ->where(['lang_code' => $code])
            ->andFilterWhere(['!=', 'id', $id])
            ->exists();

        return $exist;
    }
    
    public function makeLanguageCreateModel()
    {
        return $this->getModel();
    }
    
    public function model()
    {
        return Language::className();
    }
}