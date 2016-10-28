<?php

namespace rokorolov\parus\menu\commands;

use rokorolov\parus\menu\repositories\MenuTypeRepository;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * CreateMenuTypeHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateMenuTypeHandler
{
    use PurifierTrait;

    private $menuTypeRepository;

    public function __construct(MenuTypeRepository $menuTypeRepository)
    {
        $this->menuTypeRepository = $menuTypeRepository;
    }

    public function handle(CreateMenuTypeCommand $command)
    {
        $this->guardMenuTypeAliasIsUnique($command->getMenuTypeAlias());

        $menuType = $this->menuTypeRepository->makeMenuTypeCreateModel();
        $menuType->title = $this->textPurify($command->getTitle());
        $menuType->menu_type_aliase = $this->textPurify($command->getMenuTypeAlias());
        $menuType->description = $this->textPurify($command->getDescription());

        $this->menuTypeRepository->add($menuType);

        $command->model = $menuType;
    }

    private function guardMenuTypeAliasIsUnique($menu_type_alias)
    {
        if ($this->menuTypeRepository->existsByMenuTypeAlias($menu_type_alias)) {
            throw new LogicException('Menu type alias already exists.');
        }
    }
}
