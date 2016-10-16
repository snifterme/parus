<?php

namespace rokorolov\parus\page\commands;

use rokorolov\parus\page\repositories\PageRepository;
use rokorolov\parus\admin\exceptions\LogicException;

/**
 * ChangePageStatusHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class ChangePageStatusHandler
{
    private $pageRepository;
    
    public function __construct(
        PageRepository $pageRepository
    ) {
        $this->pageRepository = $pageRepository;
    }
    
    public function handle(ChangePageStatusCommand $command)
    {
        if (null === $page = $this->pageRepository->findById($command->getId())) {
            throw new LogicException('Page does not exist.');
        }
        
        $page->status = $command->getStatus();
        $this->pageRepository->update($page);
    }

}
