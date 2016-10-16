<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuRepository;
use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * UpdateMenuHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateMenuHandler
{
    use PurifierTrait;
    
    private $menuRepository;
    
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }
    
    public function handle(UpdateMenuCommand $command)
    {
        if (null === $menu = $this->menuRepository->findById($command->getId())) {
            throw new LogicException('Menu item does not exist.');
        }
        
        $menu->menu_type_id = $command->getMenuTypeId();
        $menu->status = $command->getStatus();
        $menu->title = $this->textPurify($command->getTitle());
        $menu->note = $this->textPurify($command->getNote());
        $menu->link = $command->getLink();
        $menu->language = $command->getLanguage();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $newParentId = (int)$command->getParentId();
            if ((int)$menu->parent_id !== $newParentId && (int)$menu->id !== $newParentId) {        
                if (null === $parent = $this->menuRepository->findById($newParentId)) {
                    throw new LogicException('Parent menu item does not exist.');
                }
                $menu->parent_id = $newParentId;
                $menu->appendTo($parent);
            } elseif ((int)$menu->id !== $newPosition = (int)$command->getPosition()) {
                switch ($newPosition)
                {
                    case Settings::orderFirst():
                        $menu->prependTo($this->menuRepository->findById($menu->parent_id));
                        break;
                    case Settings::orderLast():
                        $menu->appendTo($this->menuRepository->findById($menu->parent_id));
                        break;
                    default:
                        $menu->insertAfter($this->menuRepository->findById($newPosition));
                }
            } else {
                $this->menuRepository->update($menu);
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $menu;
    }
}