<?php

namespace RL\MainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use RL\MainBundle\Security\Factory\AnonymousSecurityFactory;


/**
 * RL\MainBundle\RLMainBundle
 *
 * @author Ax-xa-xa
 * @author Tux-oid
 */
class RLMainBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new AnonymousSecurityFactory());
    }
}
