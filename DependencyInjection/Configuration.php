<?php

namespace WebDL\CrawltrackBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webdl_crawltrack');

        /*
        $rootNode
            ->children()
            ->scalarNode('use_reverse_dns')
            ->info('Use reverse DNS check to check if the IP used are really valid. Can slow your site down a lot')
            ->defaultFalse()
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end();*/

        return $treeBuilder;
    }
}
