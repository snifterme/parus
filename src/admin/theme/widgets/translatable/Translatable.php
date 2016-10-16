<?php

namespace rokorolov\parus\admin\theme\widgets\translatable;

use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\translatable\TranslatableAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use Closure;

/**
 * This is the Translatable widget.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Translatable extends Widget
{
    /**
     *
     * @var string 
     */
    public $attribute;
    
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
     * @var array 
     */
    public $itemOptions;
    
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];
    
    /**
     *
     * @var closure 
     */
    public $translationCreator;
    
    /**
     *
     * @var array 
     */
    public $translations = [];
    
    /**
     *
     * @var boolean 
     */
    public $encode = true;
    
    /**
     *
     * @var type 
     */
    public $translationAttribute = 'language';
    
    /**
     * @inheritdoc
     */
    public function init() 
    {
        parent::init();
        
        if ($this->languages === null) {
            throw new InvalidConfigException("You must setup the 'languages' property.");
        }
        
        if ($this->attribute === null) {
            throw new InvalidConfigException("You must setup the 'attribute' property.");
        }
        
        Html::addCssClass($this->itemOptions, 'translatable-field lang-{language}');
    }

    /**
     * 
     * @return mixed
     */
    public function run()
    {
        $this->registerScripts();
        return $this->renderTranslation();
    }
    
    /**
     * 
     * @return string
     */
    public function renderTranslation()
    {
        $tag = ArrayHelper::remove($this->itemOptions, 'tag', 'div');

        foreach ($this->languages as $key => $language) {
            $item = $this->getTranslation($key, $this->attribute);
            $itemOptions = str_replace('{language}', $key, $this->itemOptions);
            $translation[] = Html::tag($tag, $item, $itemOptions);
        }
        return implode('', $translation);
    }
    
    /**
     * 
     * @param type $language
     * @param type $attribute
     * @return type
     */
    public function getTranslation($language, $attribute = null)
    {
        $item = '';
        $attributeTranslation = null;
        
        foreach ($this->translations as $translation) {
            if ($translation->{$this->translationAttribute} === $language) {
                $attributeTranslation = $translation->{$this->attribute};
            }
        }
        
        if ($this->translationCreator instanceof Closure) {
            $item = call_user_func_array($this->translationCreator, [$language, $attributeTranslation]);
        } else {
            $item = $this->encode ? Html::encode($attributeTranslation) : $attributeTranslation;
        }
        return $item;
    }
    
    /**
     * Register client scripts.
     */
    public function registerScripts()
    {
        TranslatableAsset::register($this->getView());
    }
}