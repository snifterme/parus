<?php

namespace rokorolov\parus\dashboard\services;

use rokorolov\parus\blog\repositories\PostReadRepository;
use Yii;

/**
 * DashboardService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DashboardService
{
    private $postReadRepository;
    
    /**
     * @inheritdoc
     */
    public function __construct(
        PostReadRepository $postReadRepository
    ) {
        $this->postReadRepository = $postReadRepository;
    }
    
    public function getPopularPost($limit)
    {
        return $this->postReadRepository->skipPresenter()->findPopularPost($limit);
    }
    
    public function getLastAddedPost($limit)
    {
        return $this->postReadRepository->skipPresenter()->findLastAddedPost($limit);
    }
    
    public function getStatusManager()
    {
        return Yii::createObject('rokorolov\parus\admin\theme\widgets\statusaction\contracts\StatusInterface');
    }
}
