<?php

namespace rokorolov\parus\admin\components;

use Yii;
use yii\web\Cookie;

 /**
 * This is the Language component.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Language
{
    /**
     *
     * @var string
     */
    public $queryParam = 'lang';

    /**
     *
     * @var string
     */
    public $sessionParam = 'lang';

    /**
     *
     * @var string
     */
    public $cookieParam = 'lang';

    /**
     * @var boolean
     */
    public $redirectHome = false;

    /**
     * @var boolean
     */
    public $defaultLanguage;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $queryValue = Yii::$app->request->post($this->queryParam);

        if ($queryValue !== null) {

            $config = [
                'name' => $this->cookieParam,
                'value' => $queryValue,
                'expire' => time() + 60 * 60 * 24 * 365,
            ];

            Yii::$app->response->cookies->add(new Cookie($config));
            Yii::$app->session->set($this->sessionParam, $queryValue);
            Yii::$app->language = $queryValue;

            if ($this->redirectHome === true) {
                return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
            }

        } elseif (Yii::$app->session->has($this->sessionParam)) {
            Yii::$app->language = Yii::$app->session->get($this->sessionParam);
        } elseif (Yii::$app->request->cookies->has($this->cookieParam)) {
            Yii::$app->language = Yii::$app->request->cookies->getValue($this->cookieParam);
        } elseif (null !== $this->defaultLanguage) {
            Yii::$app->language = $this->defaultLanguage;
        }
    }
}
