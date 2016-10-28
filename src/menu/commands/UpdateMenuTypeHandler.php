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
        $this->guardMenuTypeAliasIsUnique($command->getMenuTypeAlias(), $command->getId());

        if (null === $menuType = $this->menuTypeRepository->findById($command->getId())) {
            throw new LogicException('Menu Type does not exist.');
        }

        $menuType->title = $this->textPurify($command->getTitle());
        $menuType->menu_type_aliase = $this->textPurify($command->getMenuTypeAlias());
        $menuType->description = $this->textPurify($command->getDescription());

        $this->menuTypeRepository->update($menuType);

        $command->model = $menuType;
    }

    private function guardMenuTypeAliasIsUnique($menu_type_alias, $id)
    {
        if ($this->menuTypeRepository->existsByMenuTypeAlias($menu_type_alias, $id)) {
            throw new LogicException('Menu type alias already exists.');
        }
    }
}
