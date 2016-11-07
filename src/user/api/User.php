<?php

namespace rokorolov\parus\user\api;

use rokorolov\parus\blog\api\Post;
use rokorolov\parus\admin\base\BaseApi;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * User
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class User extends BaseApi
{
    const WITH_PROFILE = 'profile';
    const WITH_POST = 'post';

    public $options = [
        'id' => null,
        'email' => null,
        'order' => 'id',
        'group_by' => null,
        'limit' => null,
        'offset' => null,
        'user_status' => null,
        'post_group_limit' => null,
        'with' => [],
        'where' => null,
        'user_options' => []
    ];
    
    protected $userReadRepository;
    
    public function getUserBy($key, $value, $options = [])
    {
        $this->options[$key] = $value;
        
        return $this->getUser($options);
    }
    
    public function getUser($options = [])
    {
        $options = array_replace($this->options, $options);
        $with = $this->prepareRelations($options['with']);
        
        $user = $this->getUserReadRepository()
            ->andFilterWhere(['and',
                ['in', 'u.id', $options['id']],
                ['in', 'u.email', $options['email']],
                ['in', 'u.status', $options['user_status']]])
            ->orderBy('u.' . $options['order'])
            ->limit($options['limit']);
        
        !is_null($options['group_by']) && $user->groupBy('u.' . $options['group_by']);
        !is_null($options['offset']) && $user->offset($options['offset']);
        !is_null($options['where']) && $user->where($options['where']);
        
        $relations = [];

        if (isset($with[self::WITH_PROFILE])) {
            array_push($relations, self::WITH_PROFILE);
        }

        !empty($relations) && $user->with($relations);
        
        $collection = false;
        
        if (is_array($options['id']) || is_array($options['email']) || empty($options['id']) && empty($options['email'])) {
            if (empty($user = $user->findAll())) {
                return [];
            }
            $collection = true;
        } else {
            if (null === $user = $user->findOne()) {
                return null;
            }
            $user = [$user];
        }
        
        if (isset($with[self::WITH_POST])) {
            
            $postApi = new Post();
            $postOptions = array_replace($postApi->postOptions, $with[self::WITH_POST]);
            
            $userIds = ArrayHelper::getColumn($user, 'id');
                
            if (!$options['post_group_limit']) {
                $postOptions['author'] = $userIds;
                $post = $postApi->getPost($postOptions);
            } else {
                $postOptions['limit'] = $options['post_group_limit'];
                $post = $postApi->getGroupPost('created_by', $userIds, $postOptions);
            }
            
            $post = ArrayHelper::index($post, null, 'created_by');

            foreach ($user as $userItem) {
                if (isset($post[$userItem->id])) {
                    $userItem->posts = $post[$userItem->id];
                }
            }
        }

        return $collection ? $user : array_shift($user);
    }
    
    protected function getUserReadRepository()
    {
        if (null === $this->userReadRepository) {
            $this->userReadRepository = Yii::createObject('rokorolov\parus\user\repositories\UserReadRepository');
        }
        return $this->userReadRepository;
    }
}
