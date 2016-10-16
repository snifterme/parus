<?php

namespace rokorolov\parus\admin\traits;

use Yii;
use yii\helpers\Json;

/**
 * AjaxAlertTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait AjaxAlertTrait
{
    /**
     * Show alert if is ajax request.
     * 
     * @param Action $action
     * @param mixed $result
     * @return string alert box.
     */
    public function afterAction($action, $result) 
    {
        if (Yii::$app->request->isAjax && !empty($flashes = Yii::$app->session->getAllFlashes())) {
            Yii::$app->session->removeAllFlashes();
            return $result ? Json::encode(['messages' => $flashes] + Json::decode($result)) : Json::encode(['messages' => $flashes]);
        }
        return parent::afterAction($action, $result);
    }
}
