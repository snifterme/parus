<?php

namespace rokorolov\parus\blog\models\search;

use rokorolov\parus\blog\repositories\CategoryReadRepository;
use rokorolov\parus\blog\dto\CategoryManageDto;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\helpers\CategoryViewHelper;
use rokorolov\parus\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CategorySearch represents the model behind the search form about `rokorolov\parus\blog\models\Category`.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategorySearch extends Model
{
    public $id;
    public $title;
    public $user_username;
    public $status;
    public $created_at;
    public $depth;
    
    private $viewHelper;
    private $categoryReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        CategoryViewHelper $viewHelper,
        CategoryReadRepository $categoryReadRepository,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->categoryReadRepository = $categoryReadRepository;
        parent::__construct($config);
    }
    
    /**
     * 
     * @return type
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
            [['created_at'], 'default', 'value' => null],
            [['title', 'user_username'], 'safe'],
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
        $query = $this->categoryReadRepository->make()
            ->select('c.id, c.title, c.created_at, c.status, c.depth, c.created_by, u.username as user_username')
            ->leftJoin(User::tableName() . ' u', 'c.created_by = u.id')
            ->andWhere('lft > :lft', [':lft' => Settings::categoryRootId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::categoryManagePageSize()
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['lft' => SORT_ASC],
            'attributes' => [
                'id',
                'title',
                'created_at',
                'status',
                'user_username',
                'lft'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'c.id' => $this->id,
            'c.status' => $this->status,
            'DATE(c.created_at)' => $this->created_at ? Yii::$app->formatter->asDate($this->created_at, 'php:Y-m-d') : null
        ])
        ->andFilterWhere(['like', 'c.title', $this->title])
        ->andFilterWhere(['like', 'u.username', $this->user_username]);
        
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
    
    protected function transform($dataProvider)
    {
        $keys = [];
        $models = [];
        foreach($dataProvider->getModels() as $key => $model) {
            $keys[$key] = $model['id'];
            $models[$key] = Yii::createObject('rokorolov\parus\blog\presenters\CategoryPresenter', [$this->toDto($model)]);
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);
        
        return $dataProvider;
    }

    protected function toDto($data)
    {
        return new CategoryManageDto($data);
    }
}
