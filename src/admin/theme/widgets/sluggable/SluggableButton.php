<?php

namespace rokorolov\parus\admin\theme\widgets\sluggable;

use rokorolov\helpers\Html;
use Yii;
use yii\base\Widget;

/**
 * This is the SluggableButton widget.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SluggableButton extends Widget 
{
    
    /**
     * @var string 
     */
    public $action = 'generate-slug';
    
    /**
     * @var string 
     */
    public $selectorFrom;
    
    /**
     * @var string 
     */
    public $selectorTo;
    
    /**
     * @var string 
     */
    public $message;
    
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];
    
    /**
     * @var array 
     */
    public $i18n = [];
    
    /**
     * @var string 
     */
    public $clickEvent = 'generate-slug';
    
    /**
     * @var string 
     */
    protected $_messageCategory;
    
    /**
     * Initializes the widget.
     * @inheritdoc
     */
    public function init()
    {
        $this->registerTranslation();
        
        Html::addCssClass($this->options, 'btn ' . $this->clickEvent);
        $this->options['title'] = Yii::t('rokorolov/sluggable', 'Generate slug');
        
        if ($this->message === null) {
            $this->message = Yii::t('rokorolov/sluggable', 'You must fill out the title before generating a slug');
        }
        
        parent::init();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        $this->registerScripts();
        return $this->renderItem();
    }
    
    /**
     * Renders widget items.
     * 
     * @return string
     */
    public function renderItem()
    {
        return Html::a(Html::icon('random'), $this->action, $this->options);
    }
    
    /**
     * @inheritdoc
     */
    public static function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/sluggable'])) {
            Yii::$app->i18n->translations['rokorolov/sluggable'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/admin/theme/widgets/sluggable/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'rokorolov/sluggable' => 'sluggable.php',
                ]
            ];
        }
    }
    
    /**
     * Register client scripts.
     */
    public function registerScripts()
    {
         $this->getView()->registerJs("
            $('.$this->clickEvent').click(function (e) {
                e.preventDefault();
                var value = $('#$this->selectorFrom').val();
                if (value.length) {
                    App.generateSlug('#$this->selectorTo', value,  this);
                } else {
                    alert ('$this->message');
                }
                return false;
            });
        ");
    }
}