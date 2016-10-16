<?php

namespace rokorolov\parus\filemanager\widgets;

use yii\base\InvalidParamException;

/**
 * ResponsiveFileManager
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ResponsiveFileManager extends \yii\base\Widget
{
    public $fileManagerSrc;
    
    public function init()
    {
        if (null === $this->fileManagerSrc) {
            throw new InvalidParamException('The "fileManagerSrc" property must be set.');
        }
        parent::init();
    }
    
    public function run()
    {
        return '<iframe width="100%" height="700px" frameborder="0" src="' . $this->fileManagerSrc . '"> </iframe>';
    }
}