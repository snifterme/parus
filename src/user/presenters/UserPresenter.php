<?php

namespace rokorolov\parus\user\presenters;

use rokorolov\parus\user\helpers\Settings;
use rokorolov\parus\user\Module;
use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\helpers\Html;
use Yii;

/**
 * UserPresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserPresenter extends BasePresenter
{
    public function username_link($link = true)
    {
        return Html::a(Html::encode($this->wrappedObject->username), ['update', 'id' => $this->wrappedObject->id], ['data-pjax' => 0, 'class' => 'grid-title-link']);
    }
    
    public function last_login_on()
    {
        if ((int)Yii::$app->user->id === (int)$this->wrappedObject->id && $lastLoginOn = Yii::$app->session->get('user.lastLoginOn')) {
            return $lastLoginOn;
        }

        if ((string) $this->wrappedObject->profile->last_login_on === '0000-00-00 00:00:00') {
            return null;
        }

        return $this->wrappedObject->profile->last_login_on;
    }
    
    public function last_login_on_relative()
    {
        if (null === $lastLoginOn = $this->last_login_on()) {
            return Module::t('user', 'Never login');
        }
        
        if (!Settings::enableIntl()) {
            return Yii::$app->formatter->asDatetime($lastLoginOn);
        }
        
        return Yii::$app->formatter->asRelativeTime($lastLoginOn);
    }
    
    public function last_login_on_medium_with_relative($highlight = false)
    {
        if (null === $lastLoginOn = $this->last_login_on()) {
            return Module::t('user', 'Never login');
        }
        
        if (!Settings::enableIntl()) {
            return Yii::$app->formatter->asDatetime($this->last_login_on());
        }
        
        $lastLoginOnMedium = $highlight ? '<strong>' . Yii::$app->formatter->asDatetime($this->last_login_on(), 'medium') . '</strong>' : Yii::$app->formatter->asDatetime($this->last_login_on(), 'medium');
        return $lastLoginOnMedium . "<span class='text-info'><small> (" . Yii::$app->formatter->asRelativeTime($lastLoginOn) . ") </small></span>";
    }

    public function last_login_ip()
    {
        if (Yii::$app->user->id == $this->wrappedObject->id && Yii::$app->session->get('user.lastLoginIP')) {
            return Yii::$app->session->get('user.lastLoginIP');
        }

        if (!$this->wrappedObject->profile->last_login_ip) {
            return Module::t('user', 'Never login');
        }

        return $this->wrappedObject->profile->last_login_ip;
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
    
    public function updated_at_medium_with_relative($highlight = false)
    {
        if (!Settings::enableIntl()) {
            return $this->updated_at();
        }
        
        $updated = $highlight ? '<strong>' . Yii::$app->formatter->asDatetime($this->wrappedObject->updated_at, 'medium') . '</strong>' : Yii::$app->formatter->asDatetime($this->wrappedObject->updated_at, 'medium');
        return $updated . "<span class='text-info'><small> (" . Yii::$app->formatter->asRelativeTime($this->wrappedObject->updated_at) . ") </small></span>";
    }
    
    public function updated_at()
    {
        return Yii::$app->formatter->asDatetime($this->wrappedObject->updated_at);
    }
    

    /**
     * Get user avatar link.
     *
     * @return type
     */
    public function avatar_url()
    {
//        return $this->wrappedObject->profile->avatar_url;
    }
}
