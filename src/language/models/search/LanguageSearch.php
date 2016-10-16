<?php

namespace rokorolov\parus\language\models\search;

use rokorolov\parus\language\helpers\Settings;
use rokorolov\parus\language\repositories\LanguageReadRepository;
use rokorolov\parus\language\helpers\ViewHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

 /**
 * LanguageSearch represents the model behind the search form about `rokorolov\parus\language\models\Language`.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LanguageSearch extends Model
{
    public $id;
    public $title;
    public $status;
    public $lang_code;
    public $order;
    
    private $viewHelper;
    private $languageReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        ViewHelper $viewHelper,
        LanguageReadRepository $languageReadRepository,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->languageReadRepository = $languageReadRepository;
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
            [['id', 'status', 'order'], 'integer'],
            [['title', 'lang_code'], 'safe'],
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
        $query = $this->languageReadRepository->make();
          
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::languageManagePageSize()
            ],
        ]);
        
        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_ASC],
            'attributes' => [
                'id',
                'title',
                'status',
                'lang_code',
                'order',
                'created_at'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'l.id' => $this->id,
            'l.status' => $this->status,
            'l.order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'l.title', $this->title])
            ->andFilterWhere(['like', 'l.lang_code', $this->lang_code]);

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
            $models[$key] = Yii::createObject('rokorolov\parus\language\presenters\LanguagePresenter', [$this->toDto($model)]);
            $keys[$key] = $models[$key]->id;
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);

        return $dataProvider;
    }

    protected function toDto($data)
    {
        $language = $this->languageReadRepository->populate($data, false);

        return $language;
    }
}
