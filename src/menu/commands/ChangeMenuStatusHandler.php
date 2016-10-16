<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuRepository;
use rokorolov\parus\admin\exceptions\LogicException;

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
        
        $menu->status = $command->getStatus();
        $this->menuRepository->update($menu);
    }

}
