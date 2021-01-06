<?php

namespace CrossKnowledge\DeviceDetectBundle\Twig;

use CrossKnowledge\DeviceDetectBundle\Services\DeviceDetect;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DeviceDetectExtension
	extends AbstractExtension
{
	private $deviceDetect;

	public function __construct(DeviceDetect $detector)
	{
		$this->deviceDetect = $detector;
	}

	public function getFunctions(): array
	{
		return [
			new TwigFunction('is_tablet', array($this, 'isTablet')),
			new TwigFunction('is_mobile', array($this, 'isMobile')),
			new TwigFunction('is_desktop', array($this, 'isDesktop'))
		];
	}

	public function isTablet(): bool
    {
		return $this->deviceDetect->isTablet();
	}

	public function isMobile(): bool
	{
		return $this->deviceDetect->isMobile();
	}

	public function isDesktop(): bool
	{
		return $this->deviceDetect->isDesktop();
	}

	public function getName(): string
	{
		return 'device_detect';
	}
}
