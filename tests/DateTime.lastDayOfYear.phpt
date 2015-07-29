<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfYear(2015));
Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfYear(2015));
