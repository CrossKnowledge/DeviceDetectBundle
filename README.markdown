[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1ee5cbc4-db8d-4820-a490-00a673bdc713/big.png)](https://insight.sensiolabs.com/projects/1ee5cbc4-db8d-4820-a490-00a673bdc713) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CrossKnowledge/DeviceDetectBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CrossKnowledge/DeviceDetectBundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/CrossKnowledge/DeviceDetectBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/CrossKnowledge/DeviceDetectBundle/?branch=master) [![Build Status](https://travis-ci.org/CrossKnowledge/DeviceDetectBundle.svg?branch=master)](https://travis-ci.org/CrossKnowledge/DeviceDetectBundle) [![Total Downloads](https://poser.pugx.org/crossknowledge/devicedetect-bundle/downloads.svg)](https://packagist.org/packages/crossknowledge/devicedetect-bundle) [![Latest Stable Version](https://poser.pugx.org/crossknowledge/devicedetect-bundle/v/stable.svg)](https://packagist.org/packages/crossknowledge/devicedetect-bundle)

CrossKnowledge DeviceDetect Bundle
==================================

The CrossKnowledge/DeviceDetectBundle wraps matomo/device-detector with semantic D.I. configuration and twig helpers.

Features:

- Configurable cache storage
- Options to describe the DeviceDetector (discard_bot_information and skip_bot_detection)
- Twig helpers: is_tablet(), is_desktop(), is_mobile()

More will likely come depending on our needs and external suggestions.


Documentation
-------------

The source of the documentation is stored in the `Resources/doc/` folder

[Read the Documentation for master](https://github.com/CrossKnowledge/DeviceDetectBundle/blob/master/Resources/doc/index.md)

Installation
------------

All the installation instructions are located in the documentation.

Release Notes
-------------

## V2.0 (2020-01-06) 
Change underlying library from piwik/device-detector to matomo/device-detector
Minimum php version is 7.2
Upgraded phpunit version to 8

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

CrossKnowledgeDeviceDetectBundle is a [CrossKnowledge](https://crossknowledge.com) initiative.
See also the list of [contributors](https://github.com/CrossKnowledge/DeviceDetectBundle/contributors).
A couple of "distribution" (travis,readme.md, etc.) files are inspired from FriendsOfSymfony/FOSUserBundle's.

Contributions
-------------

Contributions are more than welcome.
We will try to integrate them. As long as there is no BC, anything can be suggested.


Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/CrossKnowledge/DeviceDetectBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
