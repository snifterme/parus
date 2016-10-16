<?php

namespace rokorolov\parus\admin\theme\widgets\sidenav;

use rokorolov\parus\admin\theme\widgets\sidenav\SideNavAsset;
use rokorolov\parus\admin\theme\widgets\sidenav\SlimScrollAsset;
use rokorolov\helpers\Html;
use Yii;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * This is the SideNav widget.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SideNav extends Nav
{
    /**
     * @var array 
     */
    public $containerOptions;
    
    /**
     * @var boolean 
     */
    public $activeModule = false;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->containerOptions['id'] = 'sidebar-menu';
        Html::removeCssClass($this->options, 'nav');
        
        $this->registerScripts();
    }
    
    /**
     * Renders widget items.
     * @inheritdoc
     */
    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            !isset($item['linkOptions']) && $item['linkOptions'] = [];
            $items[] = $this->renderItem($item);
        }
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'nav');
        $nav = Html::beginTag($tag, $this->containerOptions);
        $nav .= Html::beginTag('div', ['id' => 'side-menu', 'class' => 'side-menu']);
        $nav .= Html::tag('ul', implode("\n", $items), $this->options);
        $nav .= Html::endTag('div');
        $nav .= Html::endTag($tag);
        
        return $nav;
    }

    /**
     * 
     * @param string|array $item
     * @return string
     * @throws InvalidConfigException
     */
    public function renderItem($item, $subItem = false)
    {
        if (is_string($item)) {
            return $item;
        }
        
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        
        $header = false;
        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $icon = ArrayHelper::getValue($item, 'icon');
        $append = ArrayHelper::getValue($item, 'append');
        
        $subItem ? Html::addCssClass($linkOptions, 'side-menu-item-sub-link') : Html::addCssClass($linkOptions, 'side-menu-item-link');
        
        if (isset($item['header'])) {
            $header = true;
            Html::addCssClass($options, 'side-menu-title');
        }

        if (!$header && $this->activeModule && strpos(ltrim($url[0], '/'),  Yii::$app->controller->module->id . '/' .Yii::$app->controller->id) === 0) {
            $item['active'] = true;
        }
        
        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }
        
        $icon && $icon = Html::icon($icon . ' fa-fw side-menu-item-icon');
        $append && $append = Html::tag('span', $append, ['class' => 'side-menu-item-append']);

        $subMenu = null;
        if ($items !== null && is_array($items)) {
            if ($this->activateItems) {
                $items = $this->isChildActive($items, $active);
            }
            foreach ($items as $i => $subItem) {
                if (isset($subItem['visible']) && $subItem['visible'] === false) {
                    continue;
                }
                $subItems[$i] = $this->renderItem($subItem, true);
            }
            if (!empty($subItems)) {
                Html::addCssClass($options, 'has-sub');
                $append .= Html::icon('angle-right side-menu-item-indicator', [], 'span');
                $subMenu = Html::tag('ul', implode("\n", $subItems), ['class' => 'side-menu-sub']);
            }
        }
        
        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'is-active');
        }
        
        Html::addCssClass($options, 'side-menu-item');
        
        return Html::tag('li', $header ? $label : Html::a($icon . '<span>' . $label . '</span>' . $append, $url, $linkOptions) . $subMenu, $options);
    }
    
    /**
     * Check to see if a child item is active optionally activating the parent.
     * @param array $items @see items
     * @param boolean $active should the parent be active too
     * @return array @see items
     */
    protected function isChildActive($items, &$active)
    {
        foreach ($items as $i => $child) {
            if (ArrayHelper::remove($items[$i], 'active', false) || $this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'is-active');
                if ($this->activateParents) {
                    $active = true;
                }
            }
        }
        return $items;
    }
    
    /**
     * Register Client Scripts
     */
    protected function registerScripts()
    {
        SlimScrollAsset::register($this->view);
        SideNavAsset::register($this->view);
    }
}
