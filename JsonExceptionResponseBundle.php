<?php
/**
 * This file is part of the JsonExceptionResponseBundle.
 */

namespace Irontec\JsonExceptionResponseBundle;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Irontec <info@irontec.com>
 * @author ddniel16 <ddniel16>
 * @link https://github.com/irontec
 */
class JsonExceptionResponseBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

}
