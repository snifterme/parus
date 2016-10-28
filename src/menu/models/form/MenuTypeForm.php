<?php

namespace rokorolov\parus\menu\models\form;

use rokorolov\parus\menu\helpers\MenuTypeViewHelper;
use rokorolov\parus\menu\repositories\MenuTypeReadRepository;
use rokorolov\parus\menu\Module;
use yii\base\Model;

/**
 * MenuTypeForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeForm extends Model
{
    public $id;
    public $menu_type_alias;
    public $title;
    public $description;

    public $isNewRecord = true;

    private $wrappedObject;
    private $menuTypeReadRepository;
    private $viewHelper;

    public function __construct(
        MenuTypeViewHelper $viewHelper,
        MenuTypeReadRepository $menuTypeReadRepository,
        $config = []
    ) {
        $this->viewHelper = $viewHelper;
        $this->menuTypeReadRepository = $menuTypeReadRepository;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_type_alias', 'title'], 'filter', 'filter' => 'trim'],

            ['title', 'required'],
            ['title', 'string', 'max' => 128],

            ['menu_type_alias', 'required'],
            ['menu_type_alias', 'string', 'max' => 128],
            ['menu_type_alias', 'validateMenuTypeAlias'],

            ['description', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'menu_type_alias',
            'title',
            'description'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->viewHelper->getAttributeLabels();
    }

    /**
     * @inheritdoc
     */
    public function validateMenuTypeAlias($attribute)
    {
        if ($this->menuTypeReadRepository->existsByMenuTypeAlias($this->$attribute, $this->id)) {
            $this->addError($attribute,  Module::t('menu', 'This menutype alias "{value}" is already exists.', ['value' => $this->$attribute]));
        }
    }

    /**
     *
     * @return type
     */
    public function getData()
    {
        return $this->reverseTransform();
    }

    /**
     *
     * @param type $data
     * @return type
     */
    public function setData($data = null)
    {
        return $this->transform($data);
    }

    /**
     *
     * @param type $data
     * @return
     */
    protected function transform($data = null)
    {
        if ($data !== null) {
            $this->isNewRecord = false;
            $this->wrappedObject = $data;
            $this->id = $this->wrappedObject->id;
            $this->menu_type_alias = $this->wrappedObject->menu_type_alias;
            $this->title = $this->wrappedObject->title;
            $this->description = $this->wrappedObject->description;
        }

        return $this;
    }

    protected function reverseTransform()
    {
        return $this->getAttributes();
    }
}
