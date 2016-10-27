<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\CategoryRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

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

        $status = $command->getStatus();
        $category->status = $status;
        $childrens = $this->categoryRepository->findChildren($category);
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->categoryRepository->update($category);
            
            foreach($childrens as $children) {
                if ($children->status !== $status) {
                    $children->status = $status;
                    $this->categoryRepository->update($children);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

}
