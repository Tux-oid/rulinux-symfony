<?php
/**
 * @author Tux-oid
 */
namespace RL\ThemesBundle\DependencyInjection;
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
        $rootNode = $treeBuilder->root('rl_themes');
        $rootNode->children()
            ->arrayNode('theme')
            ->children()
            ->scalarNode('default')->defaultNull()->end()
            ->arrayNode('availables')
//			->addDefaultsIfNotSet()
            ->defaultValue(array())
            ->useAttributeAsKey('name')
            ->prototype('variable')->end()
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
    public function getAliasName()
    {

    }
}
