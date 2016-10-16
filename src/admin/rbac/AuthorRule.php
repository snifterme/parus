<?php

namespace rokorolov\parus\admin\rbac;

use yii\rbac\Rule;

/**
 * AuthorRule
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';
    
    public function execute($userId, $item, $params)
    {
        return isset($params['author_id']) ? (int)$params['author_id'] === (int)$userId : false;
    }
}
