<?php

namespace rokorolov\parus\menu\services;

use rokorolov\parus\menu\Module;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\menu\contracts\LinkPickerInterface;
use rokorolov\parus\blog\repositories\PostReadRepository;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * LinkPostPicker
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LinkPostPicker implements LinkPickerInterface
{
    const LINK_NAME = 'post';
    
    public $viewPath = '@rokorolov/parus/menu/views/menu/_linkItemForm';
    public $linkFormat = 'post/show?id=';
    
    private $link;
    private $postReadRepository;
    

    public function __construct(
        $link,
        PostReadRepository $postReadRepository
    ) {
        $this->link = $link;
        $this->postReadRepository = $postReadRepository;
    }

    public function getLinkPicker()
    {
        return Yii::$app->controller->renderAjax($this->viewPath, [
            'link' => $this->link,
            'label' => Module::t('menu', 'Post'),
            'placeholder' => Module::t('menu', '- Select Post -'),
            'data' => $this->getData()
        ]);
    }
    
    protected function getData()
    {
        $posts = $this->postReadRepository->findForLinkPostPicker();
        
        return ArrayHelper::map($posts, function ($array, $default) {
            return $this->linkFormat . $array['id'];
        }, 'title');
    }
    
    public function getName()
    {
        return self::LINK_NAME;
    }
    
    public static function nameOption()
    {
        return [self::LINK_NAME => Module::t('menu', 'Post')];
    }
}
