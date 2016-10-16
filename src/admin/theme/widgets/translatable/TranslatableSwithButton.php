<?php

namespace rokorolov\parus\admin\theme\widgets\translatable;

use rokorolov\parus\admin\theme\widgets\translatable\TranslatableAsset;
use rokorolov\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Dropdown;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;

/**
 * This is the TranslatableSwithButton widget.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class TranslatableSwithButton extends Widget 
{
    const TYPE_BUTTON = 1;
    const TYPE_TAB = 2;
    const TYPE_PILL = 3;
    
    /**
     * @var integer 
     */
    public $type = self::TYPE_BUTTON;
    
    /**
     * @var array 
     */
    public $itemOptions = [];
    
    /**
     *
     * @var string 
     */
    public $defaultLanguage;
    
    /**
     * @var array 
     */
    public $options = [];
    
    /**
     * @var array 
     */
    public $containerOptions = [];
    
    /**
     * Languages list.
     * 
     * [
     *  'en' => 'English'
     *  'ru' => 'Russian'
     * ]
     *
     * @var array 
     */
    public $languages;
    
    /**
     * @var string 
     */
    public $icon = 'globe';

    /**
     * @var string button size.
     */
    public $size = Html::SIZE_MEDIUM;

    /**
     * @var string button size.
     */
    public $style = Html::TYPE_DEFAULT;
    
    /**
     * @var string 
     */
    public $tagName = 'button';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (null === $this->languages) {
            throw new InvalidConfigException("You must setup the 'languages' property.");
        }
        if (null === $this->defaultLanguage) {
            throw new InvalidConfigException("You must setup the 'defaultLanguage' property.");
        }

        Html::addCssClass($this->containerOptions, 'language-switcher');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerScripts();
        return $this->renderButton();
    }
    
    /**
     * 
     * @return string
     */
    public function renderButton()
    {
        $languages = $this->languages;
        
        if (!isset($languages[$this->defaultLanguage])) {
            throw new InvalidParamException("Can't find default language code '$this->defaultLanguage' in passed languages");
        }
        
        foreach ($languages as $key => $language) {
            $items[] = [
                'label' => $language,
                'url' => "#",
                'options' => ['class' => 'language-switcher-sub language-switcher-sub-' . $key],
                'linkOptions' => [
                    'class' => 'language-switcher-sub-link',
                    'data-lang' => $key
                ]
            ];
        }
        
        if ($this->type === self::TYPE_TAB || $this->type === self::TYPE_PILL) {
            $this->containerOptions['tag'] = 'li';
            Html::addCssClass($this->containerOptions, 'dropdown');
            $this->tagName = 'a';
        } elseif ($this->type === self::TYPE_BUTTON) {
            Html::addCssClass($this->options, 'btn btn-' . $this->style . ' btn-' . $this->size);
            Html::addCssClass($this->containerOptions, 'btn-group');
        }

        Html::addCssClass($this->options, 'dropdown-toggle');
        $this->options['data-toggle'] = 'dropdown';
            
        Html::addCssClass($this->options, 'language-switcher-link');
        
        $label = '';
        
        if ($this->icon !== null) {
            $label .= Html::icon($this->icon) . ' ';
        }
        
        $label .= Html::tag('span', $languages[$this->defaultLanguage], ['class' => 'language-switcher-title']);
        
        $dropdown = '';
        
        if (count($items) > 1) {
            $dropdown = Dropdown::widget([
                'items' => $items,
                'encodeLabels' => false,
            ]);
            
            $label = $label . ' <span class="caret"></span>';
        }
        
        $button = Html::a($label, '#', $this->options);
        
        $dropdown = count($items) > 1 ? Dropdown::widget([
            'items' => $items,
            'encodeLabels' => false,
        ]) : '';
        
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'div');
        
        return implode("\n", [
            Html::beginTag($tag, $this->containerOptions),
            $button,
            $dropdown,
            Html::endTag($tag)
        ]);
    }
    
    /**
     * Register client scripts.
     */
    public function registerScripts()
    {
        $this->getView()->registerJs("
            var translatable_default_language = '$this->defaultLanguage';
        ", \yii\web\View::POS_BEGIN, 'translatable_default_language');
            
        TranslatableAsset::register($this->getView());
    }
}

