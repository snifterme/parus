<?php

namespace rokorolov\parus\settings\contracts;

/**
 * DefaultInstallInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface DefaultInstallInterface
{
    public function shouldInstallDefaults();
    
    public function getSettingParams();
    
    public function getSettingLangParams();
}
