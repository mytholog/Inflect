# Inflect

Склонятор.

## How to use Inflect

#### Initializing

``` php
<?php
require_once 'Inflect.php';

$obj = new Inflect();
```

#### Methods

``getInflectName`` - Возвращает ФИО, в нужном падеже

<ul>
<li>Первый параметр должен содержать как минимум фамилию</li>
<li>Вторым параметром передается падеж (0 - родительный, 1 - дательный, 2 - винительный, 3 - творительный, 4 - предложный)</li>
</ul>

``` php
$obj->getInflectName('Иванов Иван Иванович', 0);  // Иванова Ивана Ивановича
$obj->getInflectName('Кац Мария', 0);             // Кац Марии
```

``getGender`` - Возвращает пол по ФИО

``` php
$obj->getGender('Иванов Иван Иванович');  // male
$obj->getGender('Иванова Ирина');         // female
```

``getPlural`` - Возвращает число в нужном формате

``` php
$obj->getPlural(array('час', 'часа', 'часов'), 21, true);   // 21 час
$obj->getPlural(array('час', 'часа', 'часов'), 22);         // часа
$obj->getPlural(array('час', 'часа', 'часов'), 26);         // часов
```


## Contributing

If you want to work on Inflect, it is highly recommended that you first run the test suite in order to
check that everything is OK, and report strange behaviours or bugs. When modifying Inflect please make
sure that no warnings or notices are emitted by PHP by running the interpreter in your development
environment with the `error_reporting` variable set to `E_ALL | E_STRICT`.

## Dependencies

- PHP >= 5.2.6
- PHPUnit >= 3.5.0 (needed to run the test suite)

## Author

[Igor Gavrilov](mailto:mytholog@yandex.com)

## License

The code for Inflect is distributed under the terms of the MIT license (see LICENSE).
