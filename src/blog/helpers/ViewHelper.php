<?php

namespace rokorolov\parus\blog\helpers;

use rokorolov\parus\blog\Module;
use rokorolov\parus\blog\helpers\Settings;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * ViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ViewHelper
{
    public function getStatusOptions()
    {
        return $this->getStatusService()->getStatusOptions();
    }

    public function getStatusName($status = null)
    {
        return $this->getStatusService()->getStatusName($status);
    }
    
    public function getDefaultStatus()
    {
        return $this->getStatusService()->getStatus();
    }

    public function getStatusHtmlType($status = null)
    {
        return $this->getStatusService()->getStatusHtmlType($status);
    }

    public function getStatuses()
    {
        return $this->getStatusService()->getStatuses();
    }

    public function getStatusActions()
    {
        return $this->getStatusService()->getStatusActions();
    }

    public function getAttributeLabel($attributeName)
    {
        return isset($this->getAttributeLabels()[$attributeName]) ? $this->getAttributeLabels()[$attributeName] : Inflector::camel2words($attributeName, true);
    }

    public function transformCategoryForOptions($items, $assignRoot = true, $depthToMark = 0)
    {
        if ($assignRoot) {
            array_unshift($items, [
                'id' => 1,
                'title' => Module::t('blog', '(no parent)', [], Settings::language()),
                'depth' => 0,
            ]);
        }

        return ArrayHelper::map($items, 'id', function ($array, $default) use ($depthToMark) {
            return str_repeat(' - ', $array['depth'] - $depthToMark) . $array['title'];
        });
    }
}
