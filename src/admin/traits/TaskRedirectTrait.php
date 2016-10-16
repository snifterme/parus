<?php

namespace rokorolov\parus\admin\traits;

use Yii;

/**
 * TaskRedirectTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait TaskRedirectTrait
{
    /**
     * Redirect.
     *
     * @param string $url
     * @param boolean $processTask
     * @param integer $statusCode
     */
    public function redirect($url = null, $processTask = true, $statusCode = 302)
    {
        $action = Yii::$app->request->getBodyParam('task', false);
        if ($action && $processTask) {
            if ($target = Yii::$app->request->getBodyParam('target', false)) {
                $url = (array)$target;
            } else {
                switch ($action) {
                    case 'update':
                        return $this->refresh();
                        break;
                    case 'save_close':
                        $url = ['index'];
                        break;
                    case 'save_new':
                        $url = ['create'];
                        break;
                }
            }
        }

        return parent::redirect($url, $statusCode);
    }
}
