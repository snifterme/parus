<?php

namespace rokorolov\parus\admin\exceptions;

use yii\base\Exception;

/**
 * LogicException
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LogicException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Logic Error';
    }
}
