<?php

namespace WebDL\CrawltrackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('webdl_crawltrack');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('use_reverse_dns')
                    ->info('Use reverse DNS check to check if the IP used are really valid. Needs AMQP to be installed')
                    ->defaultFalse()
                ->end()
                ->scalarNode('ip_blacklist')
                    ->info('Global IP blacklist')
                    ->defaultValue(['127.0.0.1', '::1'])
                ->end()
                ->scalarNode('db_table_prefix')
                    ->info('Table prefix for this bundle')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
