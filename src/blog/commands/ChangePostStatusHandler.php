<?php

namespace rokorolov\parus\blog\commands;

use rokorolov\parus\blog\repositories\PostRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangePostStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePostStatusHandler
{
    private $postRepository;
    
    public function __construct(
        PostRepository $postRepository
    ) {
        $this->postRepository = $postRepository;
    }
    
    public function handle(ChangePostStatusCommand $command)
    {
        if (null === $post = $this->postRepository->findById($command->getId())) {
            throw new LogicException('Post does not exist.');
        }
        
        $post->status = $command->getStatus();
        $this->postRepository->update($post);
    }

}
