<?php

namespace rokorolov\parus\gallery\models\search;

use rokorolov\parus\gallery\dto\AlbumManageDto;
use rokorolov\parus\gallery\helpers\AlbumViewHelper;
use rokorolov\parus\gallery\repositories\AlbumReadRepository;
use rokorolov\parus\gallery\helpers\Settings;
use rokorolov\parus\gallery\models\AlbumLang;
use rokorolov\parus\gallery\models\Photo;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AlbumSearch represents the model behind the search.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AlbumSearch extends Model
{
    public $id;
    public $status;
    public $name;
    public $photo_count;
    
    private $viewHelper;
    private $albumReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        AlbumViewHelper $viewHelper,
        AlbumReadRepository $albumReadRepository,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->albumReadRepository = $albumReadRepository;
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
            [['photo_count', 'name'], 'safe'],
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
        $query = $this->albumReadRepository->make()
            ->select('a.id, a.status, al.name, COUNT(p.id) AS photo_count, a.created_at')
            ->leftJoin(AlbumLang::tableName() . ' al', 'al.album_id = a.id')
            ->leftJoin(Photo::tableName() . ' p', 'p.album_id = a.id')
            ->andWhere(['al.language' => Settings::language()])
            ->groupBy('a.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::albumManagePageSize()
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_DESC],
            'attributes' => [
                'id',
                'status',
                'name',
                'photo_count',
                'created_at'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.status' => $this->status
        ])
        ->andFilterWhere(['like', 'al.name', $this->name]);
        
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
            $models[$key] = Yii::createObject('rokorolov\parus\gallery\presenters\AlbumPresenter', [$this->toDto($model)]);
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);
        
        return $dataProvider;
    }

    protected function toDto($data)
    {
        return new AlbumManageDto($data);
    }
}
