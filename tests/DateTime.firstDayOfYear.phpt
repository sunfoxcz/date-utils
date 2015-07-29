<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfYear(2015));
Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfYear(2015));
