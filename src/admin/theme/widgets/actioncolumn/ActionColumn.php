<?php

namespace rokorolov\parus\admin\theme\widgets\actioncolumn;

use rokorolov\helpers\Html;
use Yii;
use yii\bootstrap\Button;
use yii\bootstrap\Dropdown;
use yii\helpers\ArrayHelper;
use Closure;
use yii\grid\DataColumn;
use yii\helpers\Url;

/**
 * ActionColumn
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ActionColumn extends DataColumn
{
    
    const MODE_DEFAULT = 'default';

    const MODE_CUSTOM = 'custom';
    
    /**
     * @var string 
     */
    public $controller;
    
    /**
     * @var string 
     */
    public $template = '{update} {view} {delete}';
    
    /**
     * @var boolean whether the labels for menu items should be HTML-encoded. 
     */
    public $encodeLabels = false;
    
    /**
     * @var callable 
     */
    public $urlCreator;
    
    /**
     * @var array 
     */
    public $buttons = [];
    
    /**
     * @var string 
     */
    public $deleteMessage;
    
    /**
     * @var array the HTML attributes for the container tag. 
     */
    public $containerOptions = [];
    
    /**
     * @var string button size.
     */
    public $size = Html::SIZE_TINY;
    
    /**
     *
     * @var type 
     */
    public $visibleButtons = [];
    
    /**
     * @var array 
     */
    public $i18n = [];
    
    /**
     *
     * @var string 
     */
    public $mode = self::MODE_DEFAULT;
    
    /**
     * Initializes the widget.
     */
    public function init()
    {
        Html::addCssClass($this->containerOptions, 'btn-group action-column');
        $this->initDefaultButtons();
        
        $this->registerTranslation();
        
        if ($this->deleteMessage === null) {
            $this->deleteMessage = Yii::t('rokorolov/actioncolumn', 'Are you sure you want to delete this item?');
        }
        
        parent::init();
    }
    
    /**
     * Initializes the default button rendering callbacks.
     */
    public function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                return [
                    'label' => Yii::t('rokorolov/actioncolumn', 'View'),
                    'icon' => Html::icon('eye', ['class' => 'text-warning fa-fw']),
                    'url' => $url,
                    'linkOptions' => ['data-pjax' => '0']
                ];
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                return [
                    'label' => Yii::t('rokorolov/actioncolumn', 'Edit'),
                    'icon' => Html::icon('pencil', ['class' => 'text-success fa-fw']),
                    'url' => $url,
                    'linkOptions' => ['data-pjax' => '0']
                ];
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                if ($this->mode === self::MODE_DEFAULT) {
                    $linkOptions = [
                        'data-confirm' => $this->deleteMessage,
                        'data-method' => 'post',
                        'data-pjax' => '0'
                    ];
                } else {
                    $linkOptions = [
                        'class' => ['js-data-post'],
                        'data-message' => $this->deleteMessage,
                        'data-container' => 'pjax-container',
                        'data-pjax' => '0',
                    ];
                }
                return [
                    'label' => Yii::t('rokorolov/actioncolumn', 'Delete'),
                    'icon' => Html::icon('times', ['class' => 'text-danger fa-fw']),
                    'url' => $url,
                    'linkOptions' => $linkOptions
                ];
            };
        }
    }
    
    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     *
     * @param string $action the button name (or action ID)
     * @param \yii\db\ActiveRecord $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the current row index
     * @return string the created URL
     */
    public function createUrl($action, $model, $key, $index)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string)$key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;
            
            return $params;
        }
    }
    
    /**
     * Creates a data cell content
     * 
     * @param \yii\db\ActiveRecord $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the current row index
     * @return string the created cell content
     */
    public function renderDataCellContent($model, $key, $index)
    {
        preg_match_all('/\\{([\w\-\/]+)\\}/', $this->template, $matches);
        foreach ($matches[1] as $name) {
            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                    : $this->visibleButtons[$name];
            } else {
                $isVisible = true;
            }
            
            if ($isVisible && isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);
                $item = call_user_func($this->buttons[$name], $url, $model, $key, $index);
                $items[$name] = $item;
            }
        }
        
        if (empty($items)) {
            return '';
        }
        
        $headItem = array_shift($items);
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'div');
        
        return implode("\n", [
            Html::beginTag($tag, $this->containerOptions),
            $this->renderButtonDropdown($headItem),
            $this->renderDropdown($items),
            Html::endTag('div'),
        ]);
    }
    
    /**
     * Generates the split button.
     * 
     * @return string the rendering result.
     */
    protected function renderButtonDropdown($button)
    {
        $options = $button['linkOptions'];
        Html::addCssClass($options, 'btn btn-default btn-' . $this->size);
        $options['href'] = Url::toRoute($button['url']);

        return Button::widget([
            'label' => $button['icon'],
            'encodeLabel' => false,
            'options' => $options,
            'tagName' => 'a'
        ]);
    }
    
    /**
     * Generates the dropdown menu.
     * 
     * @return string the rendering result.
     */
    protected function renderDropdown($items)
    {
        if (empty($items)) {
            return '';
        }
        
        foreach($items as $key => $button) {
            $options = $this->options;
            unset($options['id']);
            $label = $this->encodeLabels ? Html::encode($button['label']) : $button['label'];
            $buttons[] = [
                'label' => $button['icon'] . ' ' . $label,
                'url' => Url::toRoute($button['url']),
                'linkOptions' => $button['linkOptions'],
            ];
        }
     
        $splitButton = Button::widget([
            'label' => '<span class="caret"></span>',
            'encodeLabel' => false,
            'options' => ['data-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'class' => 'btn-default dropdown-toggle btn-' . $this->size],
        ]);
        
        return $splitButton . "\n" . Dropdown::widget([
            'items' => $buttons,
            'encodeLabels' => false,
            'options' => [
                'class' => 'pull-right action-column__dropdown'
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/actioncolumn'])) {
            Yii::$app->i18n->translations['rokorolov/actioncolumn'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/admin/theme/widgets/actioncolumn/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'rokorolov/actioncolumn' => 'actioncolumn.php',
                ]
            ];
        }
    }
}
