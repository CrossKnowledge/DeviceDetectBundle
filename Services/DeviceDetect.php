<?php

namespace CrossKnowledge\DeviceDetectBundle\Services;

use DeviceDetector\DeviceDetector;
use Symfony\Component\HttpFoundation\RequestStack;
use DeviceDetector\Cache\CacheInterface;

class DeviceDetect
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var DeviceDetector */
    protected $deviceDetector;

    /** @var CacheInterface */
    protected $cacheManager;

    /** @var []|null */
    protected $deviceDetectorOptions;

    public function __construct(RequestStack $stack, CacheInterface $cache, $deviceDetectorOptions)
    {
        $this->cacheManager = $cache;
        $this->requestStack = $stack;
        $this->setOptions($deviceDetectorOptions);

        if (null !== $this->requestStack && $this->requestStack->getCurrentRequest()) {
            $userAgent = $this->requestStack->getCurrentRequest()->headers->get('User-Agent');
        } else {
            // Symfony Bug ? in case of symfony event, request stack is lost
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        $this->deviceDetector = new DeviceDetector($userAgent);
        $this->deviceDetector->setCache($this->getCacheManager());

        if (!empty($this->deviceDetectorOptions['discard_bot_information'])) {
            $this->deviceDetector->discardBotInformation();
        }

        if (!empty($this->deviceDetectorOptions['skip_bot_detection'])) {
            $this->deviceDetector->skipBotDetection();
        }

        $this->deviceDetector->parse();
    }

    /**
     * @return bool
     */
    public function isTablet(): bool
    {
        return $this->deviceDetector->isTablet();
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        if ($this->deviceDetector->isTablet()) {
            return false;
        }

        return $this->deviceDetector->isMobile();
    }

    /**
     * @return bool
     */
    public function isDesktop(): bool
    {
        return $this->deviceDetector->isDesktop();
    }

    /**
     * @return CacheInterface
     */
    public function getCacheManager(): CacheInterface
    {
        return $this->cacheManager;
    }

    /**
     * @param array|null $deviceDetectorOptions
     */
    public function setOptions(?array $deviceDetectorOptions): void
    {
        $this->deviceDetectorOptions = $deviceDetectorOptions;
    }

    public function getDeviceDetector(): DeviceDetector
    {
        return $this->deviceDetector;
    }
}
