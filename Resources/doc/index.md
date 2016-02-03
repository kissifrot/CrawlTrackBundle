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

Step 3: Update the Database Schema
----------------------------------

This bundle requires you to update your database schema.
You can specify an optional  prefix for your tables (see Additional configuration below)
You will have to run the ``doctrine:schema:update`` command.

Step 4: Initialize default data
-------------------------------

This bundle ships with preconfigured crawlers/robots.
To initialize its database you will have to run the ``crawltrack:update-data`` command.

Step 5: Add routing
-------------------


To be able to reach the bundle from your website, you have to add it to your routing page, for example:
```yaml
# app/config/routing.yml
webdl_crawltrack:
    resource: "@WebDLCrawltrackBundle/Resources/config/routing.yml"
    # Change prefix as you like
    prefix: "/crwlt"
```

Additional configuration
------------------------
```yaml
# app/config/config.yml
webdl_crawltrack:
    db_table_prefix: crwlt_
    # Table prefix for this bundle, can be left empty
    use_reverse_dns: false
    # Use reverse DNS check to check if the IP used are really valid. Needs AMQP to be installed, see documentation
    
```

Activate Reverse DNS Check (Not yet implemented)
-----------------------------------------------
To make sure IPs aren't spoofed, the bundle can perform a reverse DNS check on every IP checked. This is disabled by default
However, as it's time consuming, it will use AMQP to do async checks.





