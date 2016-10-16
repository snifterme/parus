<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\CategoryRepository;
use rokorolov\parus\blog\repositories\PostRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeleteCategoryHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeleteCategoryHandler
{
    private $categoryRepository;
    private $postRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        PostRepository $postRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
    }

    public function handle(DeleteCategoryCommand $command)
    {
        $this->guardRootAndDefaultCategory($command->getId());

        if (null === $category = $this->categoryRepository->findById($command->getId())) {
            throw new LogicException('Category does not exist.');
        }

        $posts = $this->postRepository->findManyBy('category_id', $category->id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach($posts as $post) {
                $post->category_id = Settings::categoryDefaultId();
                $this->postRepository->update($post);
            }
            $this->categoryRepository->remove($category);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    private function guardRootAndDefaultCategory($id)
    {
        if ((int)$id === (int)Settings::categoryDefaultId() || (int)$id === (int)Settings::categoryRootId()) {
            throw new LogicException("You can't delete root or default category.");
        }
    }
}
