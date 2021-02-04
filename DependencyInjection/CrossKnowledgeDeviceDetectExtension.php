<?php

namespace CrossKnowledge\DeviceDetectBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CrossKnowledgeDeviceDetectExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $definition = $container->findDefinition('crossknowledge.device_detect');
        if (!empty($configs['cache_manager'])) {
            $definition->replaceArgument(1, new Reference($configs['cache_manager']));
        }

        if (!empty($configs['device_detector_options'])) {
            $definition->addArgument($configs['device_detector_options']);
        } else {
            $definition->addArgument(null);
        }
    }
}
