<?php

namespace CrossKnowledge\DeviceDetectBundle\Tests\Twig;

use CrossKnowledge\DeviceDetectBundle\Services\DeviceDetect;
use CrossKnowledge\DeviceDetectBundle\Twig\DeviceDetectExtension;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DeviceDetectExtensionTest extends TestCase
{

    /**
     * @dataProvider extensionFunctions
     * @param string $function
     * @param bool $expected
     * @param string $detectorMethod
     */
    public function testIsTablet(string $function, bool $expected, string $detectorMethod): void
    {
        $mock = $this->getDetector();

        /* @var $ext */
        /** @noinspection PhpParamsInspection */
        $ext = new DeviceDetectExtension($mock);

        $mock->expects(self::once())
            ->method($detectorMethod)
            ->willReturn($expected);

        $function = $this->getFunctionByName($ext, $function);
        self::assertNotNull($function);

        self::assertEquals(call_user_func($function->getCallable()), $expected);
    }

    /**
     * @return MockObject
     */
    public function getDetector(): MockObject
    {
        return $this->getMockBuilder(DeviceDetect::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param AbstractExtension $extension
     * @param string $name
     * @return TwigFunction
     */
    protected function getFunctionByName(AbstractExtension $extension, string $name): TwigFunction
    {
        foreach ($extension->getFunctions() as $function) {
            if ($function->getName() === $name) {
                return $function;
            }
        }

        throw new AssertionFailedError("missing extension function : $name");
    }

    public function extensionFunctions(): array
    {
        return [
            ['is_tablet', true, 'isTablet'],
            ['is_tablet', false, 'isTablet'],
            ['is_mobile', false, 'isMobile'],
            ['is_desktop', false, 'isDesktop'],
        ];
    }
}
