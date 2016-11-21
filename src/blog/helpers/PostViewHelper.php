<?php

namespace rokorolov\parus\blog\helpers;

use rokorolov\parus\blog\Module;
use rokorolov\parus\blog\helpers\ViewHelper;
use rokorolov\parus\blog\helpers\Settings;
use Yii;

/**
 * PostViewHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PostViewHelper extends ViewHelper
{
    private $statusService;
    private $categoryReadRepository;
    private $categoryOptions;

    /**
     *
     * @return array
     */
    public function getAttributeLabels()
    {
        return [
            'id' => Module::t('blog', 'Id'),
            'status' => Module::t('blog', 'Status'),
            'category_id' => Module::t('blog', 'Category'),
            'title' => Module::t('blog', 'Title'),
            'slug' => Module::t('blog', 'Slug'),
            'introtext' => Module::t('blog', 'Short description'),
            'fulltext' => Module::t('blog', 'Description'),
            'meta_title' => Module::t('blog', 'Meta Title'),
            'meta_keywords' => Module::t('blog', 'Meta Keywords'),
            'meta_description' => Module::t('blog', 'Meta Description'),
            'view' => Module::t('blog', 'View'),
            'reference' => Module::t('blog', 'Reference'),
            'version' => Module::t('blog', 'Version'),
            'language' => Module::t('blog', 'Language'),
            'hits' => Module::t('blog', 'Hits'),
            'published_at' => Module::t('blog', 'Published on'),
            'publish_up' => Module::t('blog', 'Start Publishing'),
            'publish_down' => Module::t('blog', 'Finish Publishing'),
            'created_by' => Module::t('blog', 'Author'),
            'updated_by' => Module::t('blog', 'Last edited by'),
            'created_at' => Module::t('blog', 'Created on'),
            'updated_at' => Module::t('blog', 'Last edited on'),
            'user_username' => Module::t('blog', 'Author'),
            'category_title' => Module::t('blog', 'Category'),
            'category' => Module::t('blog', 'Category'),
        ];
    }

    /**
     *
     * @return type
     */
    public function getCategoryOptions()
    {
        if ($this->categoryOptions === null) {
            $items = $this->getReadCategoryRepository()->findChildrenListAsArray(Settings::categoryRootId());
            $this->categoryOptions = $this->transformCategoryForOptions($items, false, 1);
        }
        return $this->categoryOptions;
    }

    /**
     *
     * @return type
     */
    protected function getStatusService()
    {
        if ($this->statusService === null) {
            $this->statusService = Yii::createObject('StatusService', [
                Settings::postStatuses(),
                Settings::postDefaultStatus()
            ]);
        }

        return $this->statusService;
    }

    /**
     *
     * @return type
     */
    protected function getReadCategoryRepository()
    {
        if ($this->categoryReadRepository === null) {
            $this->categoryReadRepository = Yii::createObject('rokorolov\parus\blog\repositories\CategoryReadRepository');
        }

        return $this->categoryReadRepository;
    }
}
