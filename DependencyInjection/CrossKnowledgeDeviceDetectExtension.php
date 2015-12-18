<?php

namespace CrossKnowledge\DeviceDetectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CrossKnowledgeDeviceDetectExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $definition = $container->findDefinition('crossknowledge.device_detect');
        if (!empty($config['cache_manager'])) {
            $definition->replaceArgument(1, new Reference($config['cache_manager']));
        }

        if (!empty($config['device_detector_options'])) {
            $definition->addArgument($config['device_detector_options']);
        } else {
            $definition->addArgument(null);
        }
    }
}
