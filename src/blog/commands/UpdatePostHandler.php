<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\PostRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * UpdatePostHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdatePostHandler
{
    use PurifierTrait;
    
    private $postRepository;
    
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    
    public function handle(UpdatePostCommand $command)
    {
        $this->guardSlugIsUnique($command->getSlug(), $command->getId());
        
        if (null === $post = $this->postRepository->findById($command->getId())) {
            throw new LogicException('Post does not exist.');
        }
 
        $post->category_id = $command->getCategoryId();
        $post->status = $command->getStatus();
        $post->title = $this->textPurify($command->getTitle());
        $post->slug = $this->textPurify($command->getSlug());
        $post->introtext = $this->purify($command->getIntrotext());
        $post->fulltext = $this->purify($command->getFulltext());
        $post->view = $this->textPurify($command->getView());
        $post->reference = $this->textPurify($command->getReference());
        $post->meta_title = $this->textPurify($command->getMetaTitle());
        $post->meta_keywords = $this->textPurify($command->getMetaKeywords());
        $post->meta_description = $this->textPurify($command->getMetaDescription());
        $post->language = $command->getLanguage();
        $post->version = $post->version + 1;
        $command->getPublishedAt() && $post->published_at = $this->formatDatetime($command->getPublishedAt());
        $post->publish_up = $this->formatDatetime($command->getPublishUp());
        $post->publish_down = $this->formatDatetime($command->getPublishDown());
        $post->modified_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $post->modified_by = Yii::$app->user->identity->id;
        
        $newImage = false;
        $imageFile = $command->getImageFile();
        if ($imageFile && !$imageFile->getHasError()) {
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                $imageFile->getBaseName(),
                $imageFile->tempName,
                Settings::postIntroImageUploadPath() . DIRECTORY_SEPARATOR . $post->id,
                Settings::postImageTransformations()
            ]);
            $post->image = $imageManager->getOriginalImageName();
            $newImage = true;
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->postRepository->update($post);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        
        $command->model = $post;
        
        if ($newImage) {
            $imageManager->deleteAll();
            $imageManager->save();
        }
    }

    private function guardSlugIsUnique($slug, $id)
    {
        if ($this->postRepository->existsBySlug($slug, $id)) {
            throw new LogicException('Slug already exists');
        }
    }
    
    private function formatDatetime($attribute, $format = 'php:Y-m-d H:i:s')
    {
        if ($attribute) {
            return Yii::$app->formatter->asDatetime($attribute, $format);
        }
        return null;
    }
}