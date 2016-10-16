<?php

namespace rokorolov\parus\user\models\search;

use rokorolov\parus\user\models\User;
use rokorolov\parus\user\models\Profile;
use rokorolov\parus\user\repositories\UserReadRepository;
use rokorolov\parus\user\helpers\Settings;
use rokorolov\parus\user\dto\UserDto;
use rokorolov\parus\user\dto\UserProfileDto;
use rokorolov\parus\user\helpers\ViewHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * UserSearch represents the model behind the search form.
 * 
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UserSearch extends Model
{
    public $id;
    public $username;
    public $email;
    public $role;
    public $created_at;
    public $last_login_on;
    
    private $viewHelper;
    private $userReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        ViewHelper $viewHelper,
        UserReadRepository $userReadRepository,
        $config = array()
    ) {
        $this->viewHelper = $viewHelper;
        $this->userReadRepository = $userReadRepository;
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'created_at', 'email', 'last_login_on'], 'safe'],
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
        $query = $this->userReadRepository->make()
            ->select('id, username, created_at, email, role, p.last_login_on')
            ->leftJoin(Profile::tableName() . ' p', 'p.user_id = u.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Settings::userManagePageSize()
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_ASC],
            'attributes' => [
                'id',
                'username',
                'email',
                'created_at',
                'role',
                'last_login_on' => [
                    'asc' => ['p.last_login_on' => SORT_ASC],
                    'desc' => ['p.last_login_on' => SORT_DESC],
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $this->transform($dataProvider);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'DATE(created_at)' => $this->created_at ? Yii::$app->formatter->asDate($this->created_at, 'php:Y-m-d') : null
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $this->transform($dataProvider);
    }
    
    protected function transform($dataProvider)
    {
        $keys = [];
        $models = [];
        foreach($dataProvider->getModels() as $key => $model) {
            $keys[$key] = $model['id'];
            $models[$key] = Yii::createObject('rokorolov\parus\user\presenters\UserPresenter', [$this->toDto($model)]);
        }
        $dataProvider->setKeys($keys);
        $dataProvider->setModels($models);
        
        return $dataProvider;
    }

    protected function toDto($data)
    {
        $userDto = $this->userReadRepository->populate($data, false);
        $userDto->profile = $this->userReadRepository->toProfileDto($data, false);
        
        return $userDto;
    }
}
