<?php

namespace rokorolov\parus\menu\models\form;

use rokorolov\parus\menu\helpers\MenuViewHelper;
use rokorolov\parus\menu\repositories\MenuReadRepository;
use rokorolov\parus\menu\repositories\MenuTypeReadRepository;
use rokorolov\parus\menu\services\AccessControlService;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\menu\Module;
use rokorolov\helpers\Html;
use Yii;
use yii\base\Model;

/**
 * MenuForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuForm extends Model
{
    public $id;
    public $menu_type_id;
    public $title;
    public $language;
    public $position;
    public $link;
    public $note;
    public $parent_id;
    public $status;
    public $lft;
    public $rgt;
    public $depth;

    public $isNewRecord = true;

    private $viewHelper;
    private $accessControl;
    private $wrappedObject;
    private $menuReadRepository;
    private $menuTypeReadRepository;

    public function __construct(
        MenuViewHelper $viewHelper,
        AccessControlService $accessControl,
        MenuReadRepository $menuReadRepository,
        MenuTypeReadRepository $menuTypeReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->accessControl = $accessControl;
        $this->menuReadRepository = $menuReadRepository;
        $this->menuTypeReadRepository = $menuTypeReadRepository;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'note', 'link'], 'trim'],

            ['title', 'required'],
            ['title', 'string', 'max' => 128],

            ['language', 'required'],
            ['language', 'in', 'range' => array_keys($this->getLanguageOptions())],

            ['menu_type_id', 'required'],
            ['menu_type_id', 'in', 'range' => array_keys($this->getMenuTypeOptions())],

            ['status', 'required'],
            ['status', 'default', 'value' => $this->viewHelper->getDefaultStatus()],
            ['status', 'in', 'range' => $this->viewHelper->getStatuses()],

            ['parent_id', 'required'],
            ['parent_id', 'in', 'range' => array_keys($this->getParentOptions())],

            ['position', 'required', 'when' => function ($model) {
                return !$model->isNewRecord;
            }],
            ['position', 'in', 'range' => array_keys($this->getOrderOptions())],

            ['link', 'required'],
            ['link', 'string', 'max' => 1024],

            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'menu_type_id',
            'title',
            'language',
            'link',
            'note',
            'parent_id',
            'position',
            'status',
        ];
    }

    public function attributeLabels()
    {
        return $this->viewHelper->getAttributeLabels();
    }

    public function getStatusOptions()
    {
        return $this->viewHelper->getStatusOptions();
    }

    public function getParentOptions()
    {
        $menuItems = $this->menuReadRepository->findChildrenListAsArray($this->menu_type_id, $this->lft, $this->rgt);

        return $this->viewHelper->transformMenuItemsForOptions($menuItems);
    }

    public function getMenuTypeOptions()
    {
        $menuTypes = $this->menuTypeReadRepository->findAllForOptions();

        return $this->viewHelper->transformMenuTypesForOptions($menuTypes);
    }

    public function getOrderOptions()
    {
        $orderOptions = $this->menuReadRepository->findForOrderOptions($this->menu_type_id, $this->depth);
        return $this->viewHelper->transformMenuOrderForOptions($orderOptions);
    }

    public function getCurrentStatus()
    {
        if ($this->isNewRecord) {
            return Html::a(Html::icon('circle text-info') . ' ' . Module::t('menu', 'Not set'), ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
        }
        return Html::a(Html::icon('circle text-' . $this->viewHelper->getStatusHtmlType($this->status)) . ' <strong>' . $this->viewHelper->getStatusName($this->status) . '</strong>', ['create', '#' => 'tab-meta'], ['data-toggle' => 'tab']);
    }

    public function getLanguageOptions()
    {
        return Settings::languages();
    }

    public function getLinkFromString()
    {
        return $this->wrappedObject->link_from_string();
    }

    public function getData()
    {
        return $this->reverseTransform();
    }

    public function setData($data = null)
    {
        return $this->transform($data);
    }

    protected function transform($data = null)
    {
        if ($data !== null) {
            $this->isNewRecord = false;
            $this->wrappedObject = $data;
            $this->id = $this->wrappedObject->id;
            $this->title = $this->wrappedObject->title;
            $this->menu_type_id = $this->wrappedObject->menu_type_id;
            $this->position = $this->wrappedObject->id;
            $this->link = $this->wrappedObject->link;
            $this->note = $this->wrappedObject->note;
            $this->parent_id = $this->wrappedObject->parent_id;
            $this->status = $this->wrappedObject->status;
            $this->language = $this->wrappedObject->language;
            $this->depth = $this->wrappedObject->depth;
            $this->lft = $this->wrappedObject->lft;
            $this->rgt = $this->wrappedObject->rgt;
        }

        if ($this->isNewRecord) {
            $this->status = $this->viewHelper->getDefaultStatus();
            $this->parent_id = Settings::menuRootId();
            $this->language = Settings::defaultLanguage();
        }

        return $this;
    }

    protected function reverseTransform()
    {
        return $this->getAttributes();
    }
}
