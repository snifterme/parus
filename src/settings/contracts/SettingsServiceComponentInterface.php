<?php

namespace rokorolov\parus\settings\contracts;

/**
 * SettingsServiceComponentInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface SettingsServiceComponentInterface
{
    public function getSettings();
    
    public function setSetting($param, $value);
    
    public function addSetting(array $params);
    
    public function deleteSetting($param);
}
