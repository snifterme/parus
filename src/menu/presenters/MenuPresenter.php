<?php

namespace rokorolov\parus\menu\presenters;

use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\helpers\Html;
use rokorolov\parus\admin\helpers\UrlHelper;

/**
 * MenuPresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuPresenter extends BasePresenter
{
    public function title_nested_link()
    {
        return str_repeat('<span class="gi">|&mdash;</span>', $this->wrappedObject->depth - 1) . ' ' . Html::a(Html::encode($this->wrappedObject->title), ['update', 'id' => $this->wrappedObject->id], ['data-pjax' => 0, 'class' => 'grid-title-link']);
    }
    
    public function link_to()
    {
        return Html::a($this->link_from_string(), '#', ['data-pjax' => 0]);
    }
    
    public function link_from_string()
    {
        return UrlHelper::fromString($this->wrappedObject->link);
    }
}
