<?php

namespace CrossKnowledge\DeviceDetectBundle\Twig;

class DeviceDetectExtension
	extends \Twig_Extension
{
	private $deviceDetect;

	public function __construct(\CrossKnowledge\DeviceDetectBundle\Services\DeviceDetect $detector)
	{
		$this->deviceDetect = $detector;
	}

	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('is_tablet', array($this, 'isTablet')),
			new \Twig_SimpleFunction('is_mobile', array($this, 'isMobile')),
			new \Twig_SimpleFunction('is_desktop', array($this, 'isDesktop'))
		];
	}

	public function isTablet()
	{
		return $this->deviceDetect->isTablet();
	}

	public function isMobile()
	{
		return $this->deviceDetect->isMobile();
	}

	public function isDesktop()
	{
		return $this->deviceDetect->isDesktop();
	}

	public function getName()
	{
		return 'device_detect';
	}
}
