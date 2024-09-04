<?php

namespace CrossKnowledge\DeviceDetectBundle\Services;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\Client\MobileApp;
use DeviceDetector\Parser\Device\Mobile;
use DeviceDetector\Parser\Device\Notebook;

class CkDeviceDetector extends DeviceDetector
{
    /**
     * Constructor
     *
     * @param string $userAgent UA to parse
     * @throws \Exception
     */
    public function __construct(string $userAgent = '')
    {
        if ('' !== $userAgent) {
            $this->setUserAgent($userAgent);
        }

        $this->addClientParser(new Browser());
        $this->addClientParser(new MobileApp());

        $this->addDeviceParser(new Mobile());

        // We just want to know the browser (Internet Explorer)
        // or if we are on a mobile or tablet
        // We don't need the specific device model or brand
        /*
        $this->addClientParser(new FeedReader());
        $this->addClientParser(new MobileApp());
        $this->addClientParser(new MediaPlayer());
        $this->addClientParser(new PIM());
        $this->addClientParser(new Browser());
        $this->addClientParser(new Library());

        $this->addDeviceParser(new HbbTv());
        $this->addDeviceParser(new ShellTv());
        $this->addDeviceParser(new Notebook());
        $this->addDeviceParser(new Console());
        $this->addDeviceParser(new CarBrowser());
        $this->addDeviceParser(new Camera());
        $this->addDeviceParser(new PortableMediaPlayer());
        $this->addDeviceParser(new Mobile());

        $this->addBotParser(new Bot());
        */
    }
}
