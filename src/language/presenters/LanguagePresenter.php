<?php

namespace rokorolov\parus\language\presenters;

use rokorolov\parus\language\helpers\Settings;
use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\helpers\Html;
use Yii;

/**
 * LanguagePresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguagePresenter extends BasePresenter
{
    public function title_link()
    {
        return Html::a(Html::encode($this->wrappedObject->title), ['index', 'id' => $this->wrappedObject->id], ['data-pjax' => 0, 'class' => 'grid-title-link']);
    }
    
    public function created_at_medium_with_relative($highlight = false)
    {
        if (!Settings::enableIntl()) {
            return $this->created_at();
        }
        
        $created = $highlight ? '<strong>' . Yii::$app->formatter->asDatetime($this->wrappedObject->created_at, 'medium') . '</strong>' : Yii::$app->formatter->asDatetime($this->wrappedObject->created_at, 'medium');
        return $created . "<span class='text-info'><small> (" . Yii::$app->formatter->asRelativeTime($this->wrappedObject->created_at) . ") </small></span>";
    }
    
    public function updated_at_medium_with_relative($highlight = false)
    {
        if (!Settings::enableIntl()) {
            return $this->updated_at();
        }
        
        $updated = $highlight ? '<strong>' . Yii::$app->formatter->asDatetime($this->wrappedObject->updated_at, 'medium') . '</strong>' : Yii::$app->formatter->asDatetime($this->wrappedObject->updated_at, 'medium');
        return $updated . "<span class='text-info'><small> (" . Yii::$app->formatter->asRelativeTime($this->wrappedObject->updated_at) . ") </small></span>";
    }
    
    public function created_at()
    {
        return Yii::$app->formatter->asDatetime($this->wrappedObject->created_at);
    }
    
    public function updated_at()
    {
        return Yii::$app->formatter->asDatetime($this->wrappedObject->updated_at);
    }
}
