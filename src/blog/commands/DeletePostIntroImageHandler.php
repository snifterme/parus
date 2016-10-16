<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\PostRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeletePostIntroImageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePostIntroImageHandler
{
    private $postRepository;
    
    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }
    
    public function handle(DeletePostIntroImageCommand $command)
    {
        if (null === $post = $this->postRepository->findById($command->getId())) {
            throw new LogicException('Post does not exist.');
        }
        
        if (!empty($post->image)) {
            
            $post->image = null;
            
            $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                null,
                null,
                Settings::postIntroImageUploadPath() . DIRECTORY_SEPARATOR . $post->id
            ]);
            
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $this->postRepository->update($post);
                $imageManager->deleteAll();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
}
