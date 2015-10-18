CrawlTrackBundle
================

Setup and Configuration
-----------------------

First, install the bundle with Composer:

.. code-block:: bash

    $ composer require webdl/composer require webdl/crawltrack-bundle

If everything worked, the ``WebDL/CrawltrackBundle`` can now be found
at ``webdl/crawltrack-bundle``.


Then enable the bundle inside your kernel class normally called `AppKernel.php`

``` php
<?php

public function registerBundles()
{
    // ...
    new WebDL\CrawltrackBundle\WebDLCrawltrackBundle(),
    // ...
}
```

Configuration: TODO

