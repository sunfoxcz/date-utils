<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfWeek(new \DateTime('2015-01-15')));
Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new \DateTime('2015-01-12')));
Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new \DateTime('2015-01-15')));
Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new \DateTime('2015-01-18')));
