<?php

namespace rokorolov\parus\admin\theme;

/**
 * This is the rokorolov\parus\admin\theme\Theme.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Theme extends \yii\base\Theme
{
    public $pathMap = [
        '@backend/views' => '@rokorolov/parus/admin/theme/views'
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}