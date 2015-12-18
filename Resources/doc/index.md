CrossKnowledgeDataTableBundle
================================

* [Installation](#installation)
* [Usage](#usage)

Installation
------------

### Step 1: Download the bundle

Open your command console, browse to your project and execute the following:

```sh
$ composer require crossknowledge/devicedetect-bundle
```

### Step 2: Enable the bundle

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new CrossKnowledge\DeviceDetectBundle\CrossKnowledgeDeviceDetectBundle(),
    );
}
```

### Step 3 : Configure (optional)

```yaml
cross_knowledge_device_detect:

    # The service name that will handle caching (must implement Doctrine\Common\Cache\CacheProvider))
    cache_manager:        ~

    # Available options are discard_bot_information and skip_bot_detection which are booleans
    device_detector_options:
        discard_bot_information:  true
        skip_bot_detection:   true

```

Usage
-----

```jinja
{% if is_tablet() %}
This is the tablet version
{% endif %}
```