<?php

namespace rokorolov\parus\admin\commands;

use rokorolov\parus\admin\contracts\CommandBusInterface;

/**
 * CommandBus
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
final class CommandBus implements CommandBusInterface
{
    public function execute($command)
    {
        $handler = $this->resolveHandler($command);
        $handler->handle($command);
    }
    
    public function resolveHandler($command)
    {
        return \Yii::createObject(substr(get_class($command), 0, -7) . 'Handler');
    }
}
