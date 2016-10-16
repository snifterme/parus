<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuRepository;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreateMenuHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateMenuHandler
{
    use PurifierTrait;
    
    private $menuRepository;
    
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }
    
    public function handle(CreateMenuCommand $command)
    {
        $menu = $this->menuRepository->makeMenuCreateModel();
        $menu->menu_type_id = $command->getMenuTypeId();
        $menu->status = $command->getStatus();
        $menu->parent_id = $command->getParentId();
        $menu->title = $this->textPurify($command->getTitle());
        $menu->note = $this->textPurify($command->getNote());
        $menu->link = $command->getLink();
        $menu->language = $command->getLanguage();
        
        if (null === $parent = $this->menuRepository->findById($command->getParentId())) {
            throw new LogicException('Parent menu item does not exist.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $menu->appendTo($parent);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $menu;
    }
}