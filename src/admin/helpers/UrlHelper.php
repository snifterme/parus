<?php

namespace rokorolov\parus\admin\helpers;

use yii\base\InvalidParamException;

/**
 * This is the UrlHelper.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UrlHelper extends \yii\helpers\Url
{
    /**
     * Parse a url.
     * 
     * @param string $url
     * @param array $params
     * @return array
     */
    public static function parse($url, $params = [])
    {
        if (!is_string($url)) {
            throw new InvalidParamException('Url must be a string.');
        }
        
        $uriParts = parse_url($url);
        parse_str(isset($uriParts['query']) ? $uriParts['query'] : '', $query);
        $route = empty($params) ? $query : array_merge($query, (array)$params);
        $route[0] = '/' . $uriParts['path'];
        
        return $route;
    }
    
    /**
     * Creates a URL based on the given parameters.
     * 
     * @param string $url
     * @param array $params
     * @param boolean $schema
     * @return string the generated URL.
     */
    public static function fromString($url, $params = [], $schema = false)
    {
        if (empty($url)) {
            return $url;
        }
        $route = static::parse($url, $params);
        return static::to($route, $schema);
    }
}
