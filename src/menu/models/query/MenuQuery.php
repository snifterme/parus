<?php

namespace rokorolov\parus\menu\models\query;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * This is the MenuQuery.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuQuery extends ActiveQuery
{
    /**
     * @return \rokorolov\parus\menu\models\query\MenuQuery
     */
    public function behaviors() 
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}