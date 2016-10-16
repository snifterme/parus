<?php

namespace rokorolov\parus\menu\contracts;

/**
 * DefaultInstallInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface DefaultInstallInterface
{
    public function shouldInstallDefaults();
    
    public function getSystemRootId();
    
    public function getMenuParams();
}
