<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\MainBundle\DependencyInjection;
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
		$rootNode = $treeBuilder->root('r_l_main');
//		  $rootNode->children()
//		  ->arrayNode('user')
//		  ->children()
//		  ->arrayNode('defaults')
//		  ->children()
//		  ->arrayNode('blocks')
//		  ->defaultValue(array())
//		  ->prototype('scalar')->end()
//		  ->end()
//		  ->end()
//		  ->end()
//		  ->end()
//		  ->end();
		return $treeBuilder;
	}
	public function getAliasName()
	{
		
	}
}
