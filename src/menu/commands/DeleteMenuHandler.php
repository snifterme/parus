<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteMenuHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteMenuHandler
{
    private $menuRepository;
    
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }
    
    public function handle(DeleteMenuCommand $command)
    {
        if (null === $menu = $this->menuRepository->findById($command->getId())) {
            throw new LogicException('Menu does not exist.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $this->menuRepository->removeWithChildren($menu);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $menu;
    }
}
