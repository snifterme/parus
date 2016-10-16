<?php

namespace rokorolov\parus\page\models\search;

use rokorolov\parus\page\repositories\PageReadRepository;
use rokorolov\parus\page\helpers\Settings;
use rokorolov\parus\page\dto\PageManageDto;
use rokorolov\parus\page\helpers\ViewHelper;
use rokorolov\parus\page\models\PageLang;
use rokorolov\parus\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostSearch represents the model behind the search form about `rokorolov\parus\blog\models\Page`.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PageSearch extends Model
{
    public $id;
    public $title;
    public $hits;
    public $status;
    public $created_at;
    public $user_username;
    
    private $viewHelper;
    private $pageReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        ViewHelper $viewHelper,
        PageReadRepository $pageReadRepository,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->pageReadRepository = $pageReadRepository;
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
        $query = $this->pageReadRepository->make()
            ->select('p.id, p.title, p.created_at, p.hits, p.status, p.created_by, u.username as user_username')
            ->leftJoin(User::tableName() . ' u', 'p.created_by = u.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::pageManagePageSize()
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_ASC],
            'attributes' => [
                'id',
                'title',
                'created_at',
                'hits',
                'status',
                'user_username',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'p.id' => $this->id,
            'p.status' => $this->status,
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
    
    protected function transform($dataProvider)
    {
        $keys = [];
        $models = [];
        foreach($dataProvider->getModels() as $key => $model) {
            $keys[$key] = $model['id'];
            $models[$key] = Yii::createObject('rokorolov\parus\page\presenters\PagePresenter', [$this->toDto($model)]);
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);
        
        return $dataProvider;
    }

    protected function toDto($data)
    {
        return new PageManageDto($data);
    }
}
