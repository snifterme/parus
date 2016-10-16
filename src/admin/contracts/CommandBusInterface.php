<?php

namespace rokorolov\parus\admin\contracts;

/**
 * CommandBusInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface CommandBusInterface
{
    public function execute($command);
}
