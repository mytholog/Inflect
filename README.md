# Inflect #

...

## How to use Inflect ##

``` php
<?php
require_once 'Inflect.php';

$obj = new Inflect();
echo $obj->getInflectName('Иванов Иван Иванович', 0);
```

## Contributing ##

If you want to work on Inflect, it is highly recommended that you first run the test suite in order to
check that everything is OK, and report strange behaviours or bugs. When modifying Inflect please make
sure that no warnings or notices are emitted by PHP by running the interpreter in your development
environment with the `error_reporting` variable set to `E_ALL | E_STRICT`.

## Dependencies ##

- PHP >= 5.2.6
- PHPUnit >= 3.5.0 (needed to run the test suite)

## Author ##

[Igor Gavrilov](mailto:mytholog@yandex.com)

## License ##

The code for Predis is distributed under the terms of the MIT license (see LICENSE).
