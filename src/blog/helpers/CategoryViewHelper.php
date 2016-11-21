<?php

namespace rokorolov\parus\blog\helpers;

use rokorolov\parus\blog\Module;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\blog\helpers\ViewHelper;
use Yii;

/**
 * CategoryViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CategoryViewHelper extends ViewHelper
{
    private $statusService;

    /**
     *
     * @return array
     */
    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('blog', 'Id'),
            'parent_id' => Module::t('blog', 'Parent'),
            'status' => Module::t('blog', 'Status'),
            'user_username' => Module::t('blog', 'Author'),
            'title' => Module::t('blog', 'Title'),
            'slug' => Module::t('blog', 'Slug'),
            'description' => Module::t('blog', 'Description'),
            'language' => Module::t('blog', 'Language'),
            'meta_title' => Module::t('blog', 'Meta Title'),
            'meta_keywords' => Module::t('blog', 'Meta Keywords'),
            'meta_description' => Module::t('blog', 'Meta Description'),
            'created_by' => Module::t('blog', 'Author'),
            'updated_by' => Module::t('blog', 'Last edited by'),
            'created_at' => Module::t('blog', 'Created on'),
            'updated_at' => Module::t('blog', 'Last edited on'),
            'category_title' => Module::t('blog', 'Category'),
        ];
    }

    /**
     *
     * @return type
     */
    protected function getStatusService()
    {
        if ($this->statusService === null) {
            $this->statusService = Yii::createObject('StatusService', [
                Settings::categoryStatuses(),
                Settings::categoryDefaultStatus()
            ]);
        }

        return $this->statusService;
    }
}
