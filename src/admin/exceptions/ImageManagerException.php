<?php

namespace rokorolov\parus\admin\exceptions;

use yii\base\Exception;

/**
 * ImageManagerException
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ImageManagerException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Image manager error';
    }
}
