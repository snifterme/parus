<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuTypeRepository;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * UpdateMenuTypeHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateMenuTypeHandler
{
    use PurifierTrait;

    private $menuTypeRepository;

    public function __construct(MenuTypeRepository $menuTypeRepository)
    {
        $this->menuTypeRepository = $menuTypeRepository;
    }

    public function handle(UpdateMenuTypeCommand $command)
    {
        $this->guardMenuTypeAliaseIsUnique($command->getMenuTypeAliase(), $command->getId());

        if (null === $menuType = $this->menuTypeRepository->findById($command->getId())) {
            throw new LogicException('Menu Type does not exist.');
        }

        $menuType->title = $this->textPurify($command->getTitle());
        $menuType->menu_type_aliase = $this->textPurify($command->getMenuTypeAliase());
        $menuType->description = $this->textPurify($command->getDescription());

        $this->menuTypeRepository->update($menuType);

        $command->model = $menuType;
    }

    private function guardMenuTypeAliaseIsUnique($menu_type_aliase, $id)
    {
        if ($this->menuTypeRepository->existsByMenuTypeAliase($menu_type_aliase, $id)) {
            throw new LogicException('Menu type aliase already exists.');
        }
    }
}
