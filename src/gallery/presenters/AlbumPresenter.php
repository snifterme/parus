<?php

namespace rokorolov\parus\gallery\presenters;

use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\helpers\Html;
use rokorolov\parus\gallery\helpers\Settings;
use Yii;

/**
 * PostPresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumPresenter extends BasePresenter
{
    public function title_manage_link()
    {
        return Html::a(Html::encode($this->wrappedObject->name), ['photo/index', 'id' => $this->wrappedObject->id], ['data-pjax' => 0, 'class' => 'grid-title-link']);
    }
    
    public function created_at()
    {
        return Yii::$app->formatter->asDatetime($this->wrappedObject->created_at);
    }
    
    public function created_at_date()
    {
        return Yii::$app->formatter->asDate($this->wrappedObject->created_at);
    }
    
    public function created_at_medium_with_relative($highlight = false)
    {
        if (!Settings::enableIntl()) {
            return $this->created_at();
        }
        
        $created = $highlight ? '<strong>' . Yii::$app->formatter->asDatetime($this->wrappedObject->created_at, 'medium') . '</strong>' : Yii::$app->formatter->asDatetime($this->wrappedObject->created_at, 'medium');
        return $created . "<span class='text-info'><small> (" . Yii::$app->formatter->asRelativeTime($this->wrappedObject->created_at) . ") </small></span>";
    }
    
    public function modified_at_medium_with_relative($highlight = false)
    {
        if (!Settings::enableIntl()) {
            return $this->modified_at();
        }
        
        $modified = $highlight ? '<strong>' . Yii::$app->formatter->asDatetime($this->wrappedObject->modified_at, 'medium') . '</strong>' : Yii::$app->formatter->asDatetime($this->wrappedObject->modified_at, 'medium');
        return $modified . "<span class='text-info'><small> (" . Yii::$app->formatter->asRelativeTime($this->wrappedObject->modified_at) . ") </small></span>";
    }

    public function modified_at()
    {
        return Yii::$app->formatter->asDatetime($this->wrappedObject->modified_at);
    }
    
    public function photo_count()
    {
        return Html::bsBadge(Html::encode($this->wrappedObject->photo_count), Html::TYPE_INFO);
    }
    
    public function image_original()
    {
        if (!empty($this->image)) {
            return Settings::albumIntroImageUploadSrc() . '/' . $this->wrappedObject->id  . '/' . Settings::albumIntroImageDir() . '/' . $this->wrappedObject->image . '.' . Settings::albumIntroImageExtension();
        }
        return null;
    }
    
    public function translate($language = null)
    {
        if (empty($this->wrappedObject->translations)) {
            return null;
        }
        
        $language === null && $language = Settings::language();
        
        foreach($this->wrappedObject->translations as $translation) {
            if ($translation->language === $language) {
                return $translation;
            }
        }
        return null;
    }
}
