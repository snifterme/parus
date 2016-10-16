<?php

namespace rokorolov\parus\menu\actions;

use rokorolov\parus\menu\helpers\Settings;
use rokorolov\parus\menu\services\LinkPickerResolverService;
use rokorolov\parus\menu\contracts\LinkPickerInterface;
use Yii;

 /**
 * This is the LinkPickerAction.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class LinkPickerAction extends \yii\base\Action
{
    /**
     * 
     * @return type
     * @throws \Exception
     */
    public function run()
    {
        if (null !== $link = Yii::$app->request->getBodyParam('link')) {
            if (!$resolver = (new LinkPickerResolverService($link, Settings::linkPickers()))->resolve()) {
                return null;
            }
            if (!$resolver instanceof LinkPickerInterface) {
                throw new \Exception(get_class($resolver) . ' must be an instance of ' . LinkPickerInterface::class);
            }
            return $resolver->getLinkPicker();
        }
    }
}
