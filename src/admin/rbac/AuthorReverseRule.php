<?php

namespace rokorolov\parus\admin\rbac;

use yii\rbac\Rule;

/**
 * AuthorReverseRule
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class AuthorReverseRule extends Rule
{
    public $name = 'isReverseAuthor';
    
    public function execute($userId, $item, $params)
    {
        return isset($params['author_id']) ? (int)$params['author_id'] !== (int)$userId : false;
    }
}
