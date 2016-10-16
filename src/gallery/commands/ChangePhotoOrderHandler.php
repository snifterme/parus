<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\PhotoRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangeOrderStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePhotoOrderHandler
{
    private $photoRepository;
    
    public function __construct(
        PhotoRepository $photoRepository
    ) {
        $this->photoRepository = $photoRepository;
    }
    
    public function handle(ChangePhotoOrderCommand $command)
    {
        $photos = $command->getOrder();
        
        $order = 1;
        foreach ($photos as $id) {
            if (null !== $photo = $this->photoRepository->findById($id)) {
                $photo->order = $order;
                $this->photoRepository->update($photo);
                $order++;
            }
        }
    }
}
