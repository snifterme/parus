<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\CategoryRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangeCategoryStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangeCategoryStatusHandler
{
    private $categoryRepository;
    
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function handle(ChangeCategoryStatusCommand $command)
    {
        if (null === $category = $this->categoryRepository->findById($command->getId())) {
            throw new LogicException('Category does not exist.');
        }

        $category->status = $command->getStatus();
        
        $this->categoryRepository->update($category);
    }

}
