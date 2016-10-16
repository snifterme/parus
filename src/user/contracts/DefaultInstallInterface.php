<?php

namespace rokorolov\parus\user\contracts;

/**
 * DefaultInstallInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface DefaultInstallInterface
{
    public function shouldInstallDefaults();
        
    public function getSystemId();
    
    public function getUserParams();
    
    public function getUserProfileParams();
}
