<?php

namespace rokorolov\parus\admin\base;

/**
 * BaseApi
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
abstract class BaseApi
{
    /**
     * Prepare relations
     * 
     * @param type $with
     * @return array
     */
    protected function prepareRelations($with, $relations = [])
    {
        $with = (array)$with;
        foreach($with as $key => $value) {
            if (is_array($value)) {
                $relations[$key] = $value;
            } else {
                $relations[$value] = [];
            }
        }
        return $relations;
    }
}
