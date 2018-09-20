StreamReader Collection
===============

## PHP XML Stream Reader
Reads XML from either a string or a stream, allowing the registration of callbacks when an elemnt is found that matches path.

## Installation
* [Install Zephir](https://docs.zephir-lang.com/ru/0.10/installation)
* `zephir build`
* Add `extension=streamparser` to your `php.ini`

Usage Example
-------------
```php
<?php
declare(strict_types = 1);

(new SixDreams\StreamReader\XmlStreamReader())
    ->registerCallback('/root/sport', '/root/sport/groups/group/events/event', function (string $rawXml) {
        echo 'XML: ' . $rawXml . "\n";
    });
```

## PHP JSON Stream Reader

@todo...

