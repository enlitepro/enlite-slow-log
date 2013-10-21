Enlite slow log
===============

Log slow pages in Zend Framework

INSTALL
=======

The recommended way to install is through composer.

```json
{
    "require": {
        "enlitepro/enlite-slow-log": "1.0.*"
    }
}
```

USAGE
=====

Add `EnliteSlowLog` to your `config/application.config.php` to enable module.

Create `enlite-slow-log.global.php` in `config/autoload` with configuration

```php
<?php

return array(
    'EnliteSlowLog' => array(
        // service manager alias
        // accept zend logger and PSR-3 logger like monolog
        'logger' => 'EnliteMonologService',
        // in milliseconds
        'threshold' => 1000
    )
);
```
