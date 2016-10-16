<?php

namespace rokorolov\parus\blog\presenters;

use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\helpers\Html;
use rokorolov\parus\blog\helpers\Settings;
use Yii;

/**
 * PostPresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostPresenter extends BasePresenter
{
    public function title_manage_link()
    {
        return Html::a(Html::encode($this->wrappedObject->title), ['update', 'id' => $this->wrappedObject->id], ['data-pjax' => 0, 'class' => 'grid-title-link']);
    }
    
    public function published_at()
    {
        return $this->isValidDate($this->wrappedObject->published_at) ? Yii::$app->formatter->asDatetime($this->wrappedObject->published_at) : null;
    }
    
    public function published_at_medium()
    {
        if (!Settings::enableIntl()) {
            return $this->published_at();
        }
        return Yii::$app->formatter->asDatetime($this->wrappedObject->published_at, 'medium');
    }
    
    public function publish_up()
    {
        return $this->isValidDate($this->wrappedObject->publish_up) ? Yii::$app->formatter->asDatetime($this->wrappedObject->publish_up) : null;
    }
    
    public function publish_up_medium()
    {
        if (!Settings::enableIntl()) {
            return $this->publish_up();
        }
        return Yii::$app->formatter->asDatetime($this->wrappedObject->publish_up, 'medium');
    }
    
    public function publish_down()
    {
        return $this->isValidDate($this->wrappedObject->publish_down) ? Yii::$app->formatter->asDatetime($this->wrappedObject->publish_down) : null;
    }
    
    public function publish_down_medium()
    {
        if (!Settings::enableIntl()) {
            return $this->publish_down();
        }
        return Yii::$app->formatter->asDatetime($this->wrappedObject->publish_down, 'medium');
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
            return Settings::postIntroImageUploadSrc() . '/' . $this->wrappedObject->id  . '/' . $this->wrappedObject->image . '.' . Settings::postImageExtension();
        }
        return null;
    }
    
    public function hits()
    {
        return Html::bsBadge(Html::encode($this->wrappedObject->hits), Html::TYPE_INFO);
    }

    protected function isValidDate($value)
    {
        if (empty($value) || (string) $value === '0000-00-00 00:00:00') {
            return false;
        }
        
        return true;
    }
}
