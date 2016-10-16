<?php

namespace rokorolov\parus\admin\theme\widgets\toolbar;

use rokorolov\helpers\Html;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Button;
use yii\base\Widget;

/**
 * This is the Toolbar widget.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Toolbar extends Widget
{
    const FORM_SELECTOR = 'js-toolbar-form';

    const TRIGGER_SELECTOR = 'js-toolbar-trigger';

    const BUTTON_NEW = 'new';

    const BUTTON_CREATE = 'create';

    const BUTTON_SAVE_CLOSE = 'save_close';

    const BUTTON_SAVE_NEW = 'save_new';

    const BUTTON_UPDATE = 'update';

    const BUTTON_UPDATE_CUSTOM = 'update_custom';

    const BUTTON_CANCEL = 'cancel';

    const BUTTON_DELETE = 'delete';

    const TYPE_LINK = 'link';

    const TYPE_BUTTON = 'button';
    
    /**
     * @var array
     */
    public $buttons;
    
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];
    
    /**
     * @var string
     */
    public $deleteMessage;

    /**
     * @var string button size.
     */
    public $size = Html::SIZE_MEDIUM;

    /**
     * @var boolean whether the labels for menu items should be HTML-encoded.
     */
    public $encodeLabels = false;

    /**
     * @var string the ID of the controller.
     */
    public $controller;

    /**
     * @var boolean.
     */
    public $dropdownOpenLeft = true;

    /**
     * @var array
     */
    private $buttonHead = [];

    /**
     * @var array
     */
    private $buttonGroup = [];

    /**
     * @var array
     */
    private $dropdown = [];

    /**
     * Initializes the widget.
     *
     * @inheritdoc
     */
    public function init()
    {
        $this->registerTranslation();
        
        if ($this->deleteMessage === null) {
            $this->deleteMessage = Yii::t('rokorolov/toolbar', 'Are you sure you want to delete this item?');
        }
        Html::addCssClass($this->options, 'toolbar');
        
        parent::init();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        $this->registerScripts();
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        return Html::tag($tag, $this->renderItems(), $this->options);
    }

    /**
     * @return string
     */
    public function renderItems()
    {
        $items = $this->prepareItems();
        return implode("\n", [
            $this->renderButton($this->buttonHead),
            $this->renderDropdown($this->dropdown),
            $this->renderButtonGroup($this->buttonGroup)
        ]);
    }

    /**
     * Generates the split button.
     *
     * @return string the rendering result.
     */
    protected function renderButton($item)
    {
        if (empty($item)) {
            return '';
        }

        $label = $item['label'];
        $isEnable = ArrayHelper::remove($item, 'enable', true);
        $options = isset($item['linkOptions']) ? $item['linkOptions'] : [];
        if ($target = ArrayHelper::remove($item, 'target')) {
            $options['data-target'] = $target;
        }
        $options['href'] = $item['url'];
        Html::addCssClass($options, ['btn-' . $item['style'], 'btn-' . $this->size]);
        !$isEnable && Html::addCssClass($options, ['disabled']);

        if(isset($item['icon'])) {
            $label = Html::icon($item['icon']) . ' ' . $label;
        }

        return implode("\n", [
            Html::beginTag('div', ['class' => 'btn-group']),
            Button::widget([
                'label' => $label,
                'encodeLabel' => false,
                'options' => $options,
                'tagName' => 'a'
            ])
        ]);
    }

    /**
     * Generates the dropdown menu.
     *
     * @return string the rendering result.
     */
    protected function renderDropdown($items)
    {
        if (count($items) < 1) {
            return Html::endTag('div');
        }

        foreach($items as $key => $item) {
            $isVisible = ArrayHelper::remove($item, 'visible', true);
            if (!$isVisible) {
                continue;
            }
            $isEnable = ArrayHelper::remove($item, 'enable', true);
            $label = $item['label'];
            $linkOptions = isset($item['linkOptions']) ? $item['linkOptions'] : [];
            
            if ($target = ArrayHelper::remove($item, 'target')) {
                $linkOptions['data-target'] = $target;
            }
            
            if(isset($item['icon'])) {
                $label = Html::icon($item['icon'], ['class' => 'fa-fw text-' . $item['style']]) . ' ' . $label;
            }
            $lines[] = [
                'label' => $label,
                'url' => $isEnable ? $item['url'] : '#',
                'linkOptions' => $linkOptions,
                'options' => $isEnable ? [] : ['class' => ['disabled']]
            ];
        }

        if (empty($lines)) {
            return Html::endTag('div');
        }

        return implode("\n", [
            Button::widget([
                'label' => '<span class="caret"></span>',
                'encodeLabel' => false,
                'options' => ['data-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'class' => 'btn-' . $this->buttonHead['style'] . ' dropdown-toggle btn-' . $this->size],
            ]),
            Dropdown::widget([
                'items' => $lines,
                'encodeLabels' => false,
                'options' => [
                    'class' => $this->dropdownOpenLeft ? 'toolbar-dropdown' : 'pull-right toolbar-dropdown'
                ]
            ]),
            Html::endTag('div')
        ]);
    }

    protected function renderButtonGroup($items)
    {
        $buttons = [];
        foreach ($items as $key => $item) {
            $buttons[$key] = $this->renderButton($item) . Html::endTag('div');
        }
        if (empty($buttons)) {
            return '';
        }
        return implode("\n",
            $buttons
        );
    }

    /**
     * @return array
     * @throws InvalidParamException
     */
    protected function prepareItems()
    {
        $items = [];
        $buttonHead = [];
        $buttonGroup = [];
        $dropdown = [];
        $defaultItems = $this->getDefaultItems();
        $buttons = (array)$this->buttons;
        foreach ($buttons as $i => $itemKey) {
            $item = $itemKey;
            if (is_array($item)) {
                $itemKey = $i;
            }
            if (array_key_exists($itemKey, $defaultItems)) {
                $item = is_array($item) ? array_replace($defaultItems[$itemKey], $item) : $defaultItems[$itemKey];
//                $item = is_array($item) ? ArrayHelper::merge($defaultItems[$itemKey], $item) : $defaultItems[$itemKey];
            }
            $isVisible = ArrayHelper::getValue($item, 'visible', true);
            if (!$isVisible) {
                continue;
            }
            if (!isset($item['label'])) {
                throw new InvalidParamException("The 'label' option is required.");
            }
            $item['label'] = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
            $item['url'] = isset($item['url']) ? Url::toRoute($item['url']) : '#';
            if (!isset($item['style'])) {
                $item['style'] = 'default';
            }
            $type = ArrayHelper::getValue($item, 'type', self::TYPE_LINK);
            if (empty($buttonHead) && $type === self::TYPE_LINK) {
                $buttonHead = $item;
            } elseif ($type === self::TYPE_BUTTON) {
                $buttonGroup[$itemKey] = $item;
            } else {
                $dropdown[$itemKey] = $item;
            }
        }

        $this->buttonHead = $buttonHead;
        $this->buttonGroup = $buttonGroup;
        $this->dropdown = $dropdown;
    }

    /**
     * Default toolbar items.
     *
     * @return array
     */
    protected function getDefaultItems()
    {
        return [
            self::BUTTON_NEW => [
                'label' => Yii::t('rokorolov/toolbar', 'Add'),
                'icon' => 'plus',
                'url' => ['create'],
                'linkOptions' => ['class' => 'toolbar-new'],
                'style' => Html::TYPE_PRIMARY,
            ],
            self::BUTTON_CREATE => [
                'label' => Yii::t('rokorolov/toolbar', 'Create'),
                'icon' => 'pencil',
                'linkOptions' => ['class' => 'toolbar-apply ' . self::TRIGGER_SELECTOR],
                'style' => Html::TYPE_SUCCESS,
            ],
            self::BUTTON_SAVE_CLOSE => [
                'label' => Yii::t('rokorolov/toolbar', 'Save & Close'),
                'icon' => 'check',
                'linkOptions' => ['class' => 'toolbar-save ' . self::TRIGGER_SELECTOR, 'data-task' => 'save_close'],
                'style' => Html::TYPE_SUCCESS,
            ],
            self::BUTTON_SAVE_NEW => [
                'label' => Yii::t('rokorolov/toolbar', 'Save & New'),
                'icon' => 'plus',
                'linkOptions' => ['class' => 'toolbar-save2new ' . self::TRIGGER_SELECTOR, 'data-task' => 'save_new'],
                'style' => Html::TYPE_SUCCESS,
            ],
            self::BUTTON_UPDATE => [
                'label' => Yii::t('rokorolov/toolbar', 'Update'),
                'icon' => 'pencil',
                'linkOptions' => ['class' => 'toolbar-update ' . self::TRIGGER_SELECTOR, 'data-task' => 'update'],
                'style' => Html::TYPE_SUCCESS,
            ],
            self::BUTTON_UPDATE_CUSTOM => [
                'label' => Yii::t('rokorolov/toolbar', 'Update'),
                'icon' => 'pencil',
                'linkOptions' => ['class' => 'toolbar-new-custom'],
                'style' => Html::TYPE_SUCCESS,
            ],
            self::BUTTON_CANCEL => [
                'label' => Yii::t('rokorolov/toolbar', 'Cancel'),
                'icon' => 'times',
                'url' => ['index'],
                'linkOptions' => ['class' => 'toolbar-cancel'],
                'style' => Html::TYPE_WARNING,
                'type' => self::TYPE_BUTTON,
            ],
            self::BUTTON_DELETE => [
                'label' => Yii::t('rokorolov/toolbar', 'Delete'),
                'icon' => 'trash',
                'linkOptions' => [
                    'class' => 'toolbar-delete',
                    'title' => Yii::t('rokorolov/toolbar', 'Delete'),
                    'data-confirm' => $this->deleteMessage,
                    'data-method' => 'post'
                ],
                'style' => Html::TYPE_DANGER,
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/toolbar'])) {
            Yii::$app->i18n->translations['rokorolov/toolbar'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/admin/theme/widgets/toolbar/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'rokorolov/toolbar' => 'toolbar.php',
                ]
            ];
        }
    }
    
    /**
     * Register client scripts.
     */
    protected function registerScripts()
    {
        $this->view->registerJs("
            $('." . self::TRIGGER_SELECTOR . "').click(function (event) {
                event.preventDefault();
                var form = document.getElementsByClassName('" . self::FORM_SELECTOR . "');
                $(this).attr('data-task') !== undefined && $('<input>').attr({ type: 'hidden', value: $(this).attr('data-task'), name: 'task'}).appendTo(form);
                $(this).attr('data-target') !== undefined && $('<input>').attr({ type: 'hidden', value: $(this).attr('data-target'), name: 'target'}).appendTo(form);
                $(form).yiiActiveForm('submitForm');
            });
        ");
    }

}
