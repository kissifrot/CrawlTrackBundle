<?php

namespace WebDL\CrawltrackBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use WebDL\CrawltrackBundle\EventListener\CrawltrackRequestListener;

class WebDLCrawltrackExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (isset($config['db_table_prefix'])) {
            $container->setParameter('webdl_crawltrack.db_table_prefix', $config['db_table_prefix']);
        }
        if (isset($config['use_reverse_dns'])) {
            $container->setParameter('webdl_crawltrack.use_reverse_dns', $config['use_reverse_dns']);
        }
        $container->setParameter('webdl_crawltrack.ip_blacklist', $config['ip_blacklist']);
    }
}
