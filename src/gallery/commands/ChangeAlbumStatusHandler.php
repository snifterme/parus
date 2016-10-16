<?php

namespace rokorolov\parus\gallery\commands;

use rokorolov\parus\gallery\repositories\AlbumRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangeAlbumStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangeAlbumStatusHandler
{
    private $albumRepository;
    
    public function __construct(
        AlbumRepository $albumRepository
    ) {
        $this->albumRepository = $albumRepository;
    }
    
    public function handle(ChangeAlbumStatusCommand $command)
    {
        if (null === $album = $this->albumRepository->findById($command->getId())) {
            throw new LogicException('Album does not exist.');
        }
        
        $album->status = $command->getStatus();
        $this->albumRepository->update($album);
    }

}
