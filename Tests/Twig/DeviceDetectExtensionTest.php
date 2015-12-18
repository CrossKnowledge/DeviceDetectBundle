<?php

namespace CrossKnowledge\DeviceDetectBundle\Tests\Services;

use CrossKnowledge\DeviceDetectBundle\DependencyInjection\CrossKnowledgeDeviceDetectExtension;
use CrossKnowledge\DeviceDetectBundle\Services\DeviceDetect;
use CrossKnowledge\DeviceDetectBundle\Twig\DeviceDetectExtension;

class DeviceDetectExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function getDetector()
    {
        $detectorMock = $this->getMockBuilder(DeviceDetect::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        return $detectorMock;
    }

    protected function getFunctionByName(\Twig_Extension $extension, $name)
    {
        foreach ($extension->getFunctions() as $function) {
            if ($function->getName()==$name) {
                return $function;
            }
        }
    }

    /**
     * @dataProvider extensionFunctions
     */
    public function testIsTablet($function, $expected, $detectorMethod)
    {
        $mock = $this->getDetector();

        $ext = new DeviceDetectExtension($mock);

        $mock->expects($this->once())->method($detectorMethod)
             ->will($this->returnValue($expected));

        $function = $this->getFunctionByName($ext, $function);
        $this->assertNotNull($function);

        $this->assertEquals(call_user_func($function->getCallable()), $expected);
    }

    public function extensionFunctions()
    {
        return [
            ['is_tablet', true, 'isTablet'],
            ['is_tablet', false, 'isTablet'],
            ['is_mobile', false, 'isMobile'],
            ['is_desktop', false, 'isDesktop'],
        ];
    }
}