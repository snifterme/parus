<?php

namespace rokorolov\parus\blog\models\search;

use rokorolov\parus\blog\repositories\PostReadRepository;
use rokorolov\parus\blog\dto\PostManageDto;
use rokorolov\parus\blog\helpers\PostViewHelper;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\models\Category;
use rokorolov\parus\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostSearch represents the model behind the search form about `rokorolov\parus\blog\models\Post`.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostSearch extends Model
{
    public $id;
    public $title;
    public $hits;
    public $status;
    public $created_at;
    public $user_username;
    public $category;
    
    private $viewHelper;
    private $postReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        PostViewHelper $viewHelper,
        PostReadRepository $postReadRepository,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->postReadRepository = $postReadRepository;
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
            [['id', 'category', 'status'], 'integer'],
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
        $query = $this->postReadRepository->make()
            ->select('p.id, p.title, p.created_at, p.hits, p.status, p.created_by, u.username as user_username, c.title as category')
            ->leftJoin(Category::tableName() . ' c', 'p.category_id = c.id')
            ->leftJoin(User::tableName() . ' u', 'p.created_by = u.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::postManagePageSize()
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_DESC],
            'attributes' => [
                'id',
                'title',
                'created_at',
                'hits',
                'status',
                'user_username',
                'category',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'p.id' => $this->id,
            'p.status' => $this->status,
            'p.category_id' => $this->category,
            'DATE(p.created_at)' => $this->created_at ? Yii::$app->formatter->asDate($this->created_at, 'php:Y-m-d') : null
        ])
        ->andFilterWhere(['like', 'p.title', $this->title])
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
    
    public function getCategoryOptions()
    {
        return $this->viewHelper->getCategoryOptions();
    }
    
    protected function transform($dataProvider)
    {
        $keys = [];
        $models = [];
        foreach($dataProvider->getModels() as $key => $model) {
            $keys[$key] = $model['id'];
            $models[$key] = Yii::createObject('rokorolov\parus\blog\presenters\PostPresenter', [$this->toDto($model)]);
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);
        
        return $dataProvider;
    }

    protected function toDto($data)
    {
        return new PostManageDto($data);
    }
}
