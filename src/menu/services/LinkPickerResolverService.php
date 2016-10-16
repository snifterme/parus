<?php

namespace rokorolov\parus\menu\services;

use rokorolov\parus\menu\helpers\Settings;
use Yii;

/**
 * LinkPickerResolverService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LinkPickerResolverService
{
    private $link;
    private $resolvers;
    
    public function __construct($link, $resolvers)
    {
        $this->link = $link;
        $this->resolvers = $resolvers;
    }
    
    public function resolve()
    {
        $type = $this->link;
        if ($pos = strpos($type, '/')) {
            $type = substr($type, 0, $pos);
        }
        
        if (array_key_exists($type, Settings::linkPickers())) {
            return Yii::createObject(Settings::linkPickers()[$type], [$this->link]);
        }
        return false;
    }
}
