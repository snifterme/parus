<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\PostRepository;
use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeletePostHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePostHandler
{
    private $postRepository;
    
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    
    public function handle(DeletePostCommand $command)
    {
        if (null === $post = $this->postRepository->findById($command->getId())) {
            throw new LogicException('Post does not exist.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->postRepository->remove($post);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
