<?php

namespace rokorolov\parus\admin\exceptions;

//use yii\base\Exception;
//use yii\web\ServerErrorHttpException;

/**
 * CommandException
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CommandException extends \Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Command Error';
    }
}
