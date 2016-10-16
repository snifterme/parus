<?php

namespace rokorolov\parus\admin\exceptions;

use yii\base\Exception;

/**
 * PresenterException
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PresenterException extends Exception
{
    /**
     * Create a new not found exception.
     *
     * @param string $class
     * @param string $message
     *
     * @return void
     */
    public function __construct($class, $message)
    {
        $this->class = $class;
        parent::__construct($message);
    }
    
    /**
     * Get the class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Presenter';
    }
}
