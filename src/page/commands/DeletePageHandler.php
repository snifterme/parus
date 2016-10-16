<?php

namespace rokorolov\parus\page\commands;

use rokorolov\parus\page\repositories\PageRepository;
use rokorolov\parus\admin\exceptions\LogicException;
use Yii;

/**
 * DeletePageHandler
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DeletePageHandler
{
    private $pageRepository;
    
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }
    
    public function handle(DeletePageCommand $command)
    {
        if (null === $page = $this->pageRepository->findById($command->getId())) {
            throw new LogicException('Page does not exist.');
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->pageRepository->remove($page);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
