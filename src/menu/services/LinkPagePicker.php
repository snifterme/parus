<?php

namespace rokorolov\parus\menu\services;

use rokorolov\parus\menu\Module;
use rokorolov\parus\page\repositories\PageReadRepository;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\menu\contracts\LinkPickerInterface;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * LinkPagePicker
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LinkPagePicker implements LinkPickerInterface
{
    const LINK_NAME = 'page';
    
    public $viewPath = '@rokorolov/parus/menu/views/menu/_linkItemForm';
    public $linkFormat = 'page/view?id=';
    
    private $link;
    private $pageReadRepository;
    

    public function __construct(
        $link,
        PageReadRepository $pageReadRepository
    ) {
        $this->link = $link;
        $this->pageReadRepository = $pageReadRepository;
    }

    public function getLinkPicker()
    {
        return Yii::$app->controller->renderAjax($this->viewPath, [
            'link' => $this->link,
            'label' => Module::t('menu', 'Page'),
            'placeholder' => Module::t('menu', '- Select Page -'),
            'data' => $this->getData()
        ]);
    }
    
    protected function getData()
    {
        $pages = $this->pageReadRepository->findForLinkPagePicker();
            
        return ArrayHelper::map($pages, function ($array, $default) {
            return $this->linkFormat . $array['id'];
        }, 'title');
    }
    
    public function getName()
    {
        return self::LINK_NAME;
    }
    
    public static function nameOption()
    {
        return [self::LINK_NAME => Module::t('menu', 'Page')];
    }
}
