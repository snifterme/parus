<?php

namespace rokorolov\parus\admin\theme\widgets\bootstrapnotify;


use rokorolov\parus\admin\theme\widgets\bootstrapnotify\BootstrapNotifyAsset;

/**
 * BootstrapNotify
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class BootstrapNotify extends \yii\bootstrap\Widget
{

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $view = $this->getView();
        foreach ($flashes as $type => $data) {
            $data = (array) $data;
            foreach ($data as $i => $message) {
                $view->registerJs("$.notify({message:'$message'},{type:'$type', mouse_over:'pause'});");
            }

            $session->removeFlash($type);
        }
        BootstrapNotifyAsset::register($view);
    }
}
