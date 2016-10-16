<?php

namespace rokorolov\parus\admin\helpers;

/**
 * TagDependencyNamingHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class TagDependencyNamingHelper
{
    /**
     * Get common tag key.
     * 
     * @param string $class
     * @return string
     */
    public static function getCommonTag($class)
    {
        return $class . ':CommonTag';
    }
    
    /**
     * Get object tag key.
     * 
     * @param string $class
     * @param integer $id
     * @return string
     */
    public static function getObjectTag($class, $id)
    {
        return $class . ':ObjectTag:' . $id;
    }
}
