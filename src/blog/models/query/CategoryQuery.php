<?php

namespace rokorolov\parus\blog\models\query;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * This is the CategoryLangQuery.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryQuery extends ActiveQuery
{
    /**
     * @return \rokorolov\parus\blog\models\query\CategoryQuery
     */
    public function behaviors() 
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}