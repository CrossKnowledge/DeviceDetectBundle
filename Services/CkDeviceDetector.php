<?php

namespace CrossKnowledge\DeviceDetectBundle\Services;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\Client\MobileApp;
use DeviceDetector\Parser\Device\Mobile;
use Exception;

class CkDeviceDetector extends DeviceDetector
{
    /**
     * Constructor
     *
     * @param string $userAgent UA to parse
     * @throws Exception
     */
    public function __construct(string $userAgent = '')
    {
        // We just want to know the browser (Internet Explorer)
        // or if we are on a mobile or tablet
        // We don't need the specific device model or brand
        if ('' !== $userAgent) {
            $this->setUserAgent($userAgent);
        }

        $this->addClientParser(new Browser());
        $this->addClientParser(new MobileApp());

        $this->addDeviceParser(new Mobile());
    }
}
