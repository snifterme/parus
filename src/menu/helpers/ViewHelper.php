<?php

namespace rokorolov\parus\menu\helpers;

use yii\helpers\Inflector;

/**
 * ViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ViewHelper
{
    public function getAttributeLabel($attributeName)
    {
        return isset($this->getAttributeLabels()[$attributeName]) ? $this->getAttributeLabels()[$attributeName] : Inflector::camel2words($attributeName, true);
    }
}
