<?php
/**
 * @author Ax-xa-xa
 * @author Tux-oid
 */
namespace RL\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use RL\SecurityBundle\Security\Factory\AnonymousSecurityFactory;

class RLSecurityBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$extension = $container->getExtension('security');
		$extension->addSecurityListenerFactory(new AnonymousSecurityFactory());
	}
}
