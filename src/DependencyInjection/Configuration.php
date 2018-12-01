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

        $rootNode
            ->children()
                ->booleanNode('use_reverse_dns')
                    ->info('Use reverse DNS check to check if the IP used are really valid. Needs AMQP to be installed')
                    ->defaultFalse()
                ->end()
                ->scalarNode('db_table_prefix')
                    ->info('Table prefix for this bundle')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
