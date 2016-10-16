<?php

namespace rokorolov\parus\admin\exceptions;

use yii\base\Exception;

/**
 * PresenterException
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class TagDependencyException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'TagDependency';
    }
}
