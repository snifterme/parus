<?php

namespace rokorolov\parus\settings\contracts;

/**
 * SettingsServiceInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface SettingsServiceInterface
{
    public function getSettings();
    
    public function setSetting($param, $value);
    
    public function addSetting(array $params);
    
    public function deleteSetting($param);
}
