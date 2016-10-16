<?php

namespace rokorolov\parus\menu\models\search;

use rokorolov\parus\menu\repositories\MenuTypeReadRepository;
use rokorolov\parus\menu\repositories\MenuReadRepository;
use rokorolov\parus\menu\helpers\MenuViewHelper;
use rokorolov\parus\menu\helpers\Settings;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MenuSearch represents the model behind the search form about `rokorolov\parus\menu\models\Menu`.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuSearch extends Model
{
    public $id;
    public $title;
    public $status;
    public $link;
    public $menutype;
    
    private $menuTypeReadRepository;
    private $menuReadRepository;
    private $viewHelper;
    
    public function __construct(
        $menutype,
        MenuViewHelper $viewHelper,
        MenuTypeReadRepository $menuTypeReadRepository,
        MenuReadRepository $menuReadRepository,
        $config = array()
    ) {
        $this->menutype = $menutype;
        $this->viewHelper = $viewHelper;
        $this->menuTypeReadRepository = $menuTypeReadRepository;
        $this->menuReadRepository = $menuReadRepository;
        parent::__construct($config);
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
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->menutype = $this->menuTypeReadRepository->findFirst('mt.id', $this->menutype);

        $query = $this->menuReadRepository->make()
            ->addSelect($this->menuReadRepository->selectAttributesMap())
            ->andWhere('lft > :lft', [':lft' => Settings::menuRootId()]);
        
        if ($this->menutype) {
            $query->andWhere(['m.menu_type_id' => $this->menutype->id]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::menuManagePageSize()
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['lft' => SORT_ASC],
            'attributes' => [
                'id',
                'title',
                'link',
                'status',
                'lft'
            ]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'm.id' => $this->id,
            'm.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'm.title', $this->title]);

        return $this->transform($dataProvider);
    }
    
    public function getStatusOptions()
    {
        return $this->viewHelper->getStatusOptions();
    }
    
    public function getStatusActions()
    {
        return $this->viewHelper->getStatusActions();
    }
    
    public function getMenuTypes()
    {
        return $this->menuTypeReadRepository->findAllTypesForMenu();
    }
    
    protected function transform($dataProvider)
    {
        $keys = [];
        $models = [];
        foreach($dataProvider->getModels() as $key => $model) {
            $models[$key] = Yii::createObject('rokorolov\parus\menu\presenters\MenuPresenter', [$this->toDto($model)]);
            $keys[$key] = $models[$key]->id;
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);

        return $dataProvider;
    }

    protected function toDto($data)
    {
        $menu = $this->menuReadRepository->populate($data);
        return $menu;
    }
}
