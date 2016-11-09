<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\CategoryRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreateCategoryHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateCategoryHandler
{
    use PurifierTrait;
    
    private $categoryRepository;
    
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function handle(CreateCategoryCommand $command)
    {
        $this->guardSlugIsUnique($command->getSlug());
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $userId = Yii::$app->user->identity->id;
        
        $category = $this->categoryRepository->makeCategoryCreateModel();
        $category->parent_id = $command->getParentId();
        $category->status = $command->getStatus();
        $category->language = $command->getLanguage();
        $category->title = $this->textPurify($command->getTitle());
        $category->slug = $this->textPurify($command->getSlug());
        $category->description = $this->purify($command->getDescription());
        $category->meta_title = $this->textPurify($command->getMetaTitle());
        $category->meta_keywords = $this->textPurify($command->getMetaKeywords());
        $category->meta_description = $this->textPurify($command->getMetaDescription());
        $category->created_at = $datetime;
        $category->modified_at = $datetime;
        $category->created_by = $userId;
        $category->modified_by = $userId;
        
        if (null === $parent = $this->categoryRepository->findById($category->parent_id)) {
            throw new LogicException('Parent category does not exist.');
        }
        
        $newImage = false;
        $imageFile = $command->getImageFile();
        if ($imageFile && !$imageFile->getHasError()) {
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                $imageFile->getBaseName(),
                $imageFile->tempName,
                null,
                Settings::categoryIntroImageTransformations()
            ]);
            $category->image = $imageManager->getOriginalImageName();
            $newImage = true;
        }

        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $category->appendTo($parent);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $category;
        
        if ($newImage) {
            $imageManager->setUploadPath(Settings::categoryIntroImageUploadPath() . DIRECTORY_SEPARATOR . $category->id);
            $imageManager->save();
        }
    }
    
    private function guardSlugIsUnique($slug)
    {
        if ($this->categoryRepository->existsBySlug($slug)) {
            throw new LogicException('Slug already exists');
        }
    }
}