<?php

namespace rokorolov\parus\admin\traits;

use rokorolov\parus\admin\contracts\StatusServiceInterface;
use yii\base\InvalidParamException;
use Yii;

/**
 * StatusServiceTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait StatusServiceTrait
{
    /**
     * Status Service instance
     *
     * @var mixed
     */
    protected $serviceInstance;
    
    /**
     * Prepare a new or cached presenter instance
     *
     * @return mixed
     * @throws PresenterException
     */
    public function getStatusService()
    {
        if (!$this->serviceInstance){
            $this->serviceInstance = Yii::$container->get('rokorolov\parus\admin\services\StatusService');
        }
        return $this->serviceInstance;
    }
}
