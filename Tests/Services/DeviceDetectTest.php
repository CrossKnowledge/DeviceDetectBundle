<?php

namespace CrossKnowledge\DeviceDetectBundle\Tests\Services;

use CrossKnowledge\DeviceDetectBundle\DependencyInjection\CrossKnowledgeDeviceDetectExtension;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DeviceDetectTest extends TestCase
{
    /**
     * @param $loadAppFile
     * @return ContainerBuilder
     * @throws Exception
     */
    protected function createContainer($loadAppFile): ContainerBuilder
    {
        $extension = new CrossKnowledgeDeviceDetectExtension();
        $container = new ContainerBuilder(new ParameterBag(['kernel.cache_dir' => __DIR__.'/fixtures']));
        $container->registerExtension($extension);

        if ($loadAppFile) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/fixtures'));
            $loader->load('config.yml');
        } else {
            $extension->load([], $container);
        }

        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }

    /**
     * @return array[]
     */
    public function toggleWithAndWithoutAppConfig(): array
    {
        return [
            [true, 'crossknowledge.example_cache_override'],
            [false, 'crossknowledge.default_cache_manager_bridge'],
        ];
    }

    /**
     * @dataProvider toggleWithAndWithoutAppConfig
     * @param bool $configLoaded
     * @param string $expectedServiceName
     * @throws Exception
     */
    public function testCacheManagerDefaultIsOverridable(bool $configLoaded, string $expectedServiceName): void
    {
        $container = $this->createContainer($configLoaded);
        $definition = $container->getDefinition('crossknowledge.device_detect');
        $arguments = $definition->getArguments();

        self::assertEquals(
            $expectedServiceName,
            (string)$arguments[1],
            'Once the option cache_manager is '.($configLoaded ? 'set' : 'not set').', the service must be overridden'
        );
    }

}
