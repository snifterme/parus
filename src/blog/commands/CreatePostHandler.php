<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\PostRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\traits\PurifierTrait;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * CreatePostHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreatePostHandler
{
    use PurifierTrait;

    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(CreatePostCommand $command)
    {
        $this->guardSlugIsUnique($command->getSlug());
        
        $datetime = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
        $userId = Yii::$app->user->identity->id;

        $post = $this->postRepository->makePostCreateModel();
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
        $post->version = 1;
        $post->published_at = $command->getPublishedAt() ? $this->formatDatetime($command->getPublishedAt()) : $datetime;
        $post->publish_up = $this->formatDatetime($command->getPublishUp());
        $post->publish_down = $this->formatDatetime($command->getPublishDown());
        $post->created_at = $datetime;
        $post->updated_at = $datetime;
        $post->created_by = $userId;
        $post->updated_by = $userId;

        $newImage = false;
        $imageFile = $command->getImageFile();
        if ($imageFile && !$imageFile->getHasError()) {
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                $imageFile->getBaseName(),
                $imageFile->tempName,
                null,
                Settings::postIntroImageTransformations()
            ]);
            $post->image = $imageManager->getOriginalImageName();
            $newImage = true;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->postRepository->add($post);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $command->model = $post;

        if ($newImage) {
            $imageManager->setUploadPath(Settings::postIntroImageUploadPath() . DIRECTORY_SEPARATOR . $post->id);
            $imageManager->save();
        }
    }

    private function guardSlugIsUnique($slug)
    {
        if ($this->postRepository->existsBySlug($slug)) {
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
