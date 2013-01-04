<?php
/**
 * @author Ax-xa-xa
 */

namespace RL\MainBundle\Security\Factory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Factory for Anonymous security extension.
 */
class AnonymousSecurityFactory implements SecurityFactoryInterface
{
    protected $options = array(
        'cookie' => 'ANONYMOUS',
        'lifetime' => 31536000,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httponly' => false,
    );
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.anonymous.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('security.authentication.provider.anonymous'))
            ->replaceArgument(0, $config['key'])
        ;
        $listenerId = 'rl_main.anonymous.authentication.listener.'.$id;
        $container
            ->setDefinition($listenerId, new DefinitionDecorator('rl_main.anonymous.authentication.listener'))
            ->replaceArgument(2, $config)
        ;

        return array($providerId, $listenerId, $defaultEntryPoint);
    }
    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeDefinition $node)
    {
        $builder = $node->children();
        $builder->scalarNode('key')->isRequired()->cannotBeEmpty()->end();

        foreach ($this->options as $name => $default) {
            if (is_bool($default)) {
                $builder->booleanNode($name)->defaultValue($default);
            } else {
                $builder->scalarNode($name)->defaultValue($default);
            }
        }
    }
    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return 'pre_auth';
    }
    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'rl-anonymous';
    }
}
