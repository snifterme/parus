<?php

namespace rokorolov\parus\menu\services;

use rokorolov\parus\menu\Module;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\menu\contracts\LinkPickerInterface;
use rokorolov\parus\blog\repositories\CategoryReadRepository;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * LinkCategoryPicker
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LinkCategoryPicker implements LinkPickerInterface
{
    const LINK_NAME = 'category';
    
    public $viewPath = '@rokorolov/parus/menu/views/menu/_linkItemForm';
    public $linkFormat = 'category/show?id=';
    
    private $link;
    private $categoryReadRepository;

    public function __construct(
        $link,
        CategoryReadRepository $categoryReadRepository
    ) {
        $this->link = $link;
        $this->categoryReadRepository = $categoryReadRepository;
    }

    public function getLinkPicker()
    {
        return Yii::$app->controller->renderAjax($this->viewPath, [
            'link' => $this->link,
            'label' => Module::t('menu', 'Category'),
            'placeholder' => Module::t('menu', '- Select Category -'),
            'data' => $this->getData()
        ]);
    }
    
    protected function getData()
    {
        $categories = $this->categoryReadRepository->findForLinkCategoryPicker();
        
        return ArrayHelper::map($categories, function ($array) {
            return $this->linkFormat . $array['id'];
        }, function ($array) {
            return str_repeat(' - ', $array['depth'] - 1) . $array['title'];
        });
    }
    
    public function getName()
    {
        return self::LINK_NAME;
    }
    
    public static function nameOption()
    {
        return [self::LINK_NAME => Module::t('menu', 'Category')];
    }
}
