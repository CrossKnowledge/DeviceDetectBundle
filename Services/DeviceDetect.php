<?php

namespace CrossKnowledge\DeviceDetectBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use \Doctrine\Common\Cache\CacheProvider;

class DeviceDetect
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var \DeviceDetector\DeviceDetector */
    protected $deviceDetector;

    /** @var Cache */
    protected $cacheManager;

    /** @var  [] */
    protected $deviceDetectorOptions;

    public function __construct(RequestStack $stack, CacheProvider $cache, $deviceDetectorOptions)
    {
        $this->cacheManager = $cache;
        $this->requestStack = $stack;
        $this->setOptions($deviceDetectorOptions);

        if (null !== $this->requestStack && $this->requestStack->getCurrentRequest()) {
            $userAgent = $this->requestStack->getCurrentRequest()->headers->get('User-Agent');
        } else {
            $userAgent = '';
        }

        $this->deviceDetector = new \DeviceDetector\DeviceDetector($userAgent);
        $this->deviceDetector->setCache($this->getCacheManager());

        if (!empty($this->deviceDetectorOptions['discard_bot_information'])) {
            $this->deviceDetector->discardBotInformation();
        }

        if (!empty($this->deviceDetectorOptions['skip_bot_detection'])) {
            $this->deviceDetector->skipBotDetection();
        }

        $this->deviceDetector->parse();
    }

    public function isTablet()
    {
        return $this->deviceDetector->isTablet();
    }

    public function isMobile()
    {
        if ($this->deviceDetector->isTablet()) {
            return false;
        }

        return $this->deviceDetector->isMobile();
    }

    public function isDesktop()
    {
        return $this->deviceDetector->isDesktop();
    }

    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    public function setOptions($deviceDetectorOptions)
    {
        $this->deviceDetectorOptions = $deviceDetectorOptions;
    }

    public function getDeviceDetector()
    {
        return $this->deviceDetector;
    }
}
