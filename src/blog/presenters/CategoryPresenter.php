<?php

namespace rokorolov\parus\blog\presenters;

use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\helpers\Html;
use Yii;

/**
 * PostPresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryPresenter extends BasePresenter
{
    public function title_manage_nested_link()
    {
        return str_repeat('<span class="gi">|&mdash;</span>', $this->wrappedObject->depth - 1) . ' ' . Html::a(Html::encode($this->wrappedObject->title), ['update', 'id' => $this->wrappedObject->id], ['data-pjax' => 0, 'class' => 'grid-title-link']);
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
    
    public function image_original()
    {
        if (!empty($this->image)) {
            return Settings::categoryIntroImageUploadSrc() . '/' . $this->wrappedObject->id  . '/' . $this->wrappedObject->image . '.' . Settings::categoryIntroImageExtension();
        }
        return null;
    }
}
