<?php

namespace rokorolov\parus\menu\helpers;

use rokorolov\parus\menu\Module;
use rokorolov\parus\menu\helpers\ViewHelper;

/**
 * MenuTypeViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuTypeViewHelper extends ViewHelper
{
    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('menu', 'Id'),
            'menu_type_aliase' => Module::t('menu', 'Menu type aliase'),
            'title' => Module::t('menu', 'Title'),
            'description' => Module::t('menu', 'Description'),
        ];
    }
}
