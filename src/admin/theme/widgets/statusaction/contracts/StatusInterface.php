<?php

namespace rokorolov\parus\admin\theme\widgets\statusaction\contracts;

/**
 * StatusInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface StatusInterface
{
    public function getStatusOptions();
    
    public function getStatusActions();
    
    public function getStatusHtmlTypes();
}
