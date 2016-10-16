<?php

namespace rokorolov\parus\blog\contracts;

/**
 * DefaultInstallInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface DefaultInstallInterface
{
    public function shouldInstallDefaults();
    
    public function getSystemRootId();
    
    public function getSystemDefaultId();
    
    public function getCategoryParams();
}
