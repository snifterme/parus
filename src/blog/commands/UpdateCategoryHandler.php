<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\CategoryRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * UpdateCategoryHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateCategoryHandler
{
    use PurifierTrait;
    
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(UpdateCategoryCommand $command)
    {
        $this->guardSlugIsUnique($command->getSlug(), $command->getId());
        
        if (null === $category = $this->categoryRepository->findById($command->getId())) {
            throw new LogicException('Post does not exist.');
        }

        $category->status = $command->getStatus();
        $category->language = $command->getLanguage();
        $category->title = $this->textPurify($command->getTitle());
        $category->slug = $this->textPurify($command->getSlug());
        $category->description = $this->purify($command->getDescription());
        $category->meta_title = $this->textPurify($command->getMetaTitle());
        $category->meta_keywords = $this->textPurify($command->getMetaKeywords());
        $category->meta_description = $this->textPurify($command->getMetaDescription());
        $category->modified_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $category->modified_by = Yii::$app->user->identity->id;

        if ((int)$category->parent_id !== $newParentId = (int)$command->getParentId()) {
        
            if (null === $parent = $this->categoryRepository->findById($newParentId)) {
                throw new LogicException('Parent category does not exist.');
            }
            
            $category->appendTo($parent);
            $category->parent_id = $newParentId;
        }

        $newImage = false;
        $imageFile = $command->getImageFile();
        if ($imageFile && !$imageFile->getHasError()) {
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                $imageFile->getBaseName(),
                $imageFile->tempName,
                Settings::categoryIntroImageUploadPath() . DIRECTORY_SEPARATOR . $category->id,
                Settings::categoryIntroImageTransformations()
            ]);
            $category->image = $imageManager->getOriginalImageName();
            $newImage = true;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->categoryRepository->update($category);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $command->model = $category;

        if ($newImage) {
            $imageManager->deleteAll();
            $imageManager->save();
        }
    }
    
    private function guardSlugIsUnique($slug, $id)
    {
        if ($this->categoryRepository->existsBySlug($slug, $id)) {
            throw new LogicException('Slug already exists');
        }
    }
}
