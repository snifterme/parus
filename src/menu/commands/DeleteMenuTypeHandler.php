<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuRepository;
use rokorolov\parus\menu\repositories\MenuTypeRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteMenuTypeHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteMenuTypeHandler
{
    private $menuRepository;
    private $menuTypeRepository;
    
    public function __construct(
        MenuRepository $menuRepository,
        MenuTypeRepository $menuTypeRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuTypeRepository = $menuTypeRepository;
    }
    
    public function handle(DeleteMenuTypeCommand $command)
    {
        if (null === $menuType = $this->menuTypeRepository->findById($command->getId())) {
            throw new LogicException('Menu Type does not exist.');
        }
        
        $menuItems = $this->menuRepository->findManyBy('menu_type_id', $menuType->id);
                
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            foreach ($menuItems as $menu) {
                $this->menuRepository->remove($menu);
            }
            $this->menuTypeRepository->remove($menuType);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
