<?php

namespace rokorolov\parus\admin\actions;

use yii\helpers\Inflector;

 /**
 * This is the SlugGeneratorAction.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SlugGeneratorAction extends \yii\base\Action
{
    /**
     * Returns a string with all spaces converted to given replacement.
     * 
     * @param string $value
     * @return string
     */
    public function run($value)
    {
        return Inflector::slug($value);
    }
}
