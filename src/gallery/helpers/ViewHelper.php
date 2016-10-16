<?php

namespace rokorolov\parus\gallery\helpers;

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
    
    public function getStatuses()
    {
        return $this->getStatusService()->getStatuses();
    }

    public function getStatusName($status = null)
    {
        return $this->getStatusService()->getStatusName($status);
    }

    public function getStatusHtmlType($status = null)
    {
        return $this->getStatusService()->getStatusHtmlType($status);
    }
    
    public function getStatusActions()
    {
        return $this->getStatusService()->getStatusActions();
    }
    
    public function getDefaultStatus()
    {
        return $this->getStatusService()->getStatus();
    }
    
    public function getAttributeLabel($attributeName)
    {
        return isset($this->getAttributeLabels()[$attributeName]) ? $this->getAttributeLabels()[$attributeName] : Inflector::camel2words($attributeName, true);
    }
}
