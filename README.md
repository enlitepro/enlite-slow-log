Enlite slow log
===============

Log slow pages in Zend Framework 2

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

Configure with Zend\Log
-----------------------

```php
// prepare log
'log' => array(
    'MySlowLog' => array(
        'writers' => array(
            array(
                'name' => 'Zend\Log\Writer\Stream',
                'options' => array(
                    "stream" => "data/slow.log"
                )
            )
        )
    )
)
//
'EnliteSlowLog' => array(
    'logger' => 'MySlowLog',
    'threshold' => 1000
),
```

Log will be write to `data/slow.log`

Configure with EnliteMonolog
----------------------------

Install [EnliteMonolog](https://github.com/enlitepro/enlite-monolog)

```php
'EnliteMonolog' => array(
    'MySlowLog' => array(
        'name' => 'SlowLog', // will be output to log
        'handlers' => array(
            'default' => array(
                'name' => 'Monolog\Handler\StreamHandler',
                'args' => array(
                    'path' => 'data/slow.log'
                )
            )
        )
    )
),

'EnliteSlowLog' => array(
    'logger' => 'MySlowLog',
    'threshold' => 1000
)
```

Log will be write to `data/slow.log`