<?php

namespace rokorolov\parus\admin\contracts;

/**
 * HasTagDependency
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface HasTagDependency
{
    public function getDependencyTagId();
}
