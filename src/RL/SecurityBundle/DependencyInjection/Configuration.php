<?php
/**
 *@author Ax-xa-xa
 */
namespace RL\SecurityBundle\DependencyInjection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rl_security');
        $rootNode->children()
            ->arrayNode('anonymous')
            ->children()
            ->scalarNode('class')->isRequired()->end()
            ->arrayNode('defaults')
            ->defaultValue(array())
            ->useAttributeAsKey('name')
            ->prototype('variable')->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
    public function getAliasName()
    {
        return '';
    }
}
