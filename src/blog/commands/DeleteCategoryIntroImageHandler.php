<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\CategoryRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteCategoryIntroImageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteCategoryIntroImageHandler
{
    private $categoryRepository;
    
    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function handle(DeleteCategoryIntroImageCommand $command)
    {
        if (null === $category = $this->categoryRepository->findById($command->getId())) {
            throw new LogicException('Category does not exist.');
        }
        
        if (!empty($category->image)) {
            
            $category->image = null;
            
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                null,
                null,
                Settings::categoryIntroImageUploadPath() . DIRECTORY_SEPARATOR . $category->id
            ]);
            
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $this->categoryRepository->update($category);
                $imageManager->deleteAll();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
}
