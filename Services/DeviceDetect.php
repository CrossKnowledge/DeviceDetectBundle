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
    }

    /**
     * @return bool
     */
    public function isTablet(): bool
    {
        return $this->getDeviceDetector()->isTablet();
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        if ($this->getDeviceDetector()->isTablet()) {
            return false;
        }

        return $this->getDeviceDetector()->isMobile();
    }

    /**
     * @return bool
     */
    public function isDesktop(): bool
    {
        return $this->getDeviceDetector()->isDesktop();
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

    /**
     * @return string user agent deduced from requestStack or $_SERVER['HTTP_USER_AGENT'] if not available
     * '' if no user agent found
     */
    protected function getUserAgent(): string {
        if (null !== $this->requestStack && $this->requestStack->getCurrentRequest()) {
            return $this->requestStack->getCurrentRequest()->headers->get('User-Agent') ?? '';
        }

        // Symfony Bug ? in case of symfony event, request stack is lost
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Lazy loading of DeviceDetector
     * @return DeviceDetector
     */
    public function getDeviceDetector(): DeviceDetector {
        if (null !== $this->deviceDetector) {
            return $this->deviceDetector;
        }
        $this->deviceDetector = new DeviceDetector($this->getUserAgent());
        $this->deviceDetector->setCache($this->getCacheManager());

        if (!empty($this->deviceDetectorOptions['discard_bot_information'])) {
            $this->deviceDetector->discardBotInformation();
        }

        if (!empty($this->deviceDetectorOptions['skip_bot_detection'])) {
            $this->deviceDetector->skipBotDetection();
        }

        $this->deviceDetector->parse();

        return $this->deviceDetector;
    }
}
