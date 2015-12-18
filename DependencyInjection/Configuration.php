<?php

namespace CrossKnowledge\DeviceDetectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cross_knowledge_device_detect');

        $rootNode
            ->children()
                ->scalarNode('cache_manager')->info('The service name that will handle caching (must implement Doctrine\Common\Cache\CacheProvider))')
                ->end()
                ->arrayNode('device_detector_options')
                    ->info("Available options are discard_bot_information and skip_bot_detection which are booleans")
                        ->children()
                            ->booleanNode('discard_bot_information')
                                ->defaultTrue()
                            ->end()
                            ->booleanNode('skip_bot_detection')
                                ->defaultTrue()
                            ->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
