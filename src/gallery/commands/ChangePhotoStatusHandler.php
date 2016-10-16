<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\PhotoRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangePhotoStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePhotoStatusHandler
{
    private $photoRepository;
    
    public function __construct(
        PhotoRepository $photoRepository
    ) {
        $this->photoRepository = $photoRepository;
    }
    
    public function handle(ChangePhotoStatusCommand $command)
    {
        if (null === $photo = $this->photoRepository->findById($command->getId())) {
            throw new LogicException('Photo does not exist.');
        }
        
        $photo->status = $command->getStatus();
        $this->photoRepository->update($photo);
    }

}
