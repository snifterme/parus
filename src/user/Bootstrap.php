<?php

namespace rokorolov\parus\user;

use rokorolov\parus\user\commands\AfterUserLoginCommand;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\User;

/**
 * This is the Bootstrap of rokorolov\parus\user\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->resolveDependency();
        $this->registerSettings();
    }
    
    protected function resolveDependency()
    {
        Yii::$container->set('rokorolov\parus\user\repositories\UserRepository', 'rokorolov\parus\user\repositories\UserRepository');
        Yii::$container->set('rokorolov\parus\user\repositories\UserReadRepository', 'rokorolov\parus\user\repositories\UserReadRepository');
        Yii::$container->set('yii\web\IdentityInterface', 'rokorolov\parus\user\services\IdentityService');
    }
    
    /**
     * @inheritdoc
     */
    protected function registerSettings()
    {
        Event::on(User::class, User::EVENT_BEFORE_LOGIN, function ($event) {
            $commandBus = Yii::createObject('rokorolov\parus\admin\contracts\CommandBusInterface');
            $commandBus->execute(new AfterUserLoginCommand(
                $event->identity->getId()
            ));
        });
    }
}
