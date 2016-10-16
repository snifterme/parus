<?php

namespace rokorolov\parus\admin\contracts;

/**
 * RbacServiceInterface
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
interface RbacServiceInterface
{
    public function init();
    
    public function getOptions();
    
    public function getRoles();
    
    public function getRoleSuperAdmin();
}
