Installation
============

Step 1: Download the Bundle
---------------------------

```bash
$ composer require webdl/composer require webdl/crawltrack-bundle
```

If everything worked, the ``WebDL/CrawltrackBundle`` can now be found
at ``webdl/crawltrack-bundle``.

Step 2: Enable the Bundle
-------------------------


Then enable the bundle inside your kernel class normally called `AppKernel.php`

```php
<?php
// app/AppKernel.php

// ...
public function registerBundles()
{
    // ...
    new WebDL\CrawltrackBundle\WebDLCrawltrackBundle(),
    // ...
}
```

To be able to reach the bundle from your website, you have to add it to your routing page, for example:
```yaml
# app/config/routing.yml
wdl_crawltrack:
    resource: "@WebDLCrawltrackBundle/Resources/config/routing.yml"
    # Change prefix as you like
    prefix: "/crwlt"
```

Configuration: TODO

