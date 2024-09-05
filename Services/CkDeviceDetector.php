<?php

namespace CrossKnowledge\DeviceDetectBundle\Services;

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\Client\MobileApp;
use DeviceDetector\Parser\Device\Mobile;
use DeviceDetector\Parser\OperatingSystem;
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
        if ('' !== $userAgent) {
            $this->setUserAgent($userAgent);
        }

        $this->addClientParser(new Browser());
        $this->addClientParser(new MobileApp());

        $this->addDeviceParser(new Mobile());
        // We just want to know the browser (Internet Explorer)
        // or if we are on a mobile or tablet
        // We don't need the specific device model or brand
    }

    public function isTablet(): bool
    {
        return parent::isTablet();
    }

    public function isMobile(): bool
    {
        // Client hints indicate a mobile device
        if ($this->clientHints instanceof ClientHints && $this->clientHints->isMobile()) {
            return true;
        }

        return !$this->isBot() && !$this->isDesktop();
    }

    public function isDesktop(): bool
    {
        $osName = $this->getOsAttribute('name');

        if (empty($osName) || self::UNKNOWN === $osName) {
            return false;
        }

        // Check for browsers available for mobile devices only
        if ($this->usesMobileBrowser()) {
            return false;
        }

        return OperatingSystem::isDesktopOs($osName);
    }

    public function parse(): void
    {
        if ($this->isParsed()) {
            return;
        }

        $this->parsed = true;

        // skip parsing for empty useragents or those not containing any letter (if no client hints were provided)
        if ((empty($this->userAgent) || !\preg_match('/([a-z])/i', $this->userAgent))
            && empty($this->clientHints)
        ) {
            return;
        }

        $this->parseBot();

        if ($this->isBot()) {
            return;
        }

        $this->parseOs();

        /**
         * Parse Clients
         * Clients might be browsers, Feed Readers, Mobile Apps, Media Players or
         * any other application accessing with an parseable UA
         */
        $this->parseClient();

        $this->parseDevice();
    }
}
