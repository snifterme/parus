<?php

namespace rokorolov\parus\menu\helpers;

use rokorolov\parus\menu\Module;
use rokorolov\parus\menu\helpers\ViewHelper;
use rokorolov\parus\menu\helpers\Settings;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * MenuViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class MenuViewHelper extends ViewHelper
{
    private $statusService;

    public function getStatusOptions()
    {
        return $this->getStatusService()->getStatusOptions();
    }

    public function getStatusName($status = null)
    {
        return $this->getStatusService()->getStatusName($status);
    }
    
    public function getStatuses()
    {
        return $this->getStatusService()->getStatuses();
    }

    public function getDefaultStatus()
    {
        return $this->getStatusService()->getStatus();
    }

    public function getStatusHtmlType($status = null)
    {
        return $this->getStatusService()->getStatusHtmlType($status);
    }
    
    public function getStatusActions()
    {
        return $this->getStatusService()->getStatusActions();
    }

    public function getMenuTypeOptions()
    {
        $pickers = Settings::linkPickers();

        $options = [];
        foreach($pickers as $picker) {
            $options = array_merge($options, $picker::nameOption());
        }

        return $options;
    }

    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('menu', 'Id'),
            'title' => Module::t('menu', 'Title'),
            'menu_type_id' => Module::t('menu', 'Menu Location'),
            'link' => Module::t('menu', 'Link'),
            'language' => Module::t('menu', 'Language'),
            'note' => Module::t('menu', 'Note'),
            'parent_id' => Module::t('menu', 'Parent Item'),
            'status' => Module::t('menu', 'Status'),
            'position' => Module::t('menu', 'Ordering'),
            'linkPreview' => Module::t('menu', 'Link'),
        ];
    }

    public function transformMenuItemsForOptions($items, $assignRoot = true, $depthToMark = 0)
    {
        if ($assignRoot) {
            array_unshift($items, [
                'id' => Settings::menuRootId(),
                'title' => Module::t('menu', 'Menu Item Root'),
                'depth' => 0,
            ]);
        }

        return ArrayHelper::map($items, 'id', function ($array, $default) use ($depthToMark) {
            return str_repeat(' - ', $array['depth'] - $depthToMark) . $array['title'];
        });
    }

    public function transformMenuTypesForOptions($items)
    {
        return ArrayHelper::map($items, 'id', 'title');
    }

    public function transformMenuOrderForOptions($items)
    {
        return [Settings::orderFirst() => Module::t('menu', '- First -')] + ArrayHelper::map($items, 'id', 'title') + [Settings::orderLast() => Module::t('menu', '- Last -')];
    }

    protected function getStatusService()
    {
        if ($this->statusService === null) {
            $this->statusService = Yii::createObject('StatusService', [
                Settings::menuStatuses(),
                Settings::menuDefaultStatus()
            ]);
        }

        return $this->statusService;
    }
}
