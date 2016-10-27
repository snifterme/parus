<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * ChangeMenuStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangeMenuStatusHandler
{
    private $menuRepository;
    
    public function __construct(
        MenuRepository $menuRepository
    ) {
        $this->menuRepository = $menuRepository;
    }
    
    public function handle(ChangeMenuStatusCommand $command)
    {
        if (null === $menu = $this->menuRepository->findById($command->getId())) {
            throw new LogicException('Menu does not exist.');
        }

        $status = $command->getStatus();
        $menu->status = $status;
        $childrens = $this->menuRepository->findChildren($menu);
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->menuRepository->update($menu);
            
            foreach($childrens as $children) {
                if ($children->status !== $status) {
                    $children->status = $status;
                    $this->menuRepository->update($children);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}
