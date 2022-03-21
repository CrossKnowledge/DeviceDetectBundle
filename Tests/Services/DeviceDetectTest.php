<?php

namespace CrossKnowledge\DeviceDetectBundle\Tests\Services;

use CrossKnowledge\DeviceDetectBundle\DependencyInjection\CrossKnowledgeDeviceDetectExtension;
use CrossKnowledge\DeviceDetectBundle\Services\DeviceDetect;
use DeviceDetector\Cache\CacheInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * Data provider to test multiple user agents:
     * [user-agent, isDesktop, isMobile, isTablet]
     *
     * @return array
     */
    public function userAgentProvider(): array
    {
        return [
            ['Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)', true, false, false],
            ['Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M)', false, true, false],
            ['Mozilla/5.0 (Linux; Android 4.4.3; KFTHWI Build/KTU84M)', false, false, true]
        ];
    }

    /**
     * Returns a RequestStack instance with the provided userAgent in the header.
     *
     * @param string $userAgent
     *
     * @return RequestStack
     */
    private function getRequestStackMock(string $userAgent): RequestStack
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [], [], [], ['HTTP_USER_AGENT' => $userAgent]));

        return $requestStack;
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

    /**
     * Test the device detector through multiple user agents.
     *
     * @dataProvider userAgentProvider
     *
     * @param string $userAgent
     * @param bool   $isDesktop
     * @param bool   $isMobile
     * @param bool   $isTablet
     */
    public function testUserAgentDetector(string $userAgent, bool $isDesktop, bool $isMobile, bool $isTablet): void
    {
        $deviceDetect = new DeviceDetect(
            $this->getRequestStackMock($userAgent),
            $this->getMockBuilder(CacheInterface::class)->getMock(),
            []
        );

        $this->assertEquals($isDesktop, $deviceDetect->isDesktop());
        $this->assertEquals($isMobile, $deviceDetect->isMobile());
        $this->assertEquals($isTablet, $deviceDetect->isTablet());
    }
}
