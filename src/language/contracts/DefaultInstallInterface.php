<?php

namespace rokorolov\parus\language\contracts;

/**
 * DefaultInstallInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface DefaultInstallInterface
{
    public function shouldInstallDefaults();
        
    public function getSystemId();
    
    public function getSystemCode();
    
    public function getLanguageParams();
}
