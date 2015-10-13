<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfWeek(new \DateTime('2015-01-15')));
Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new \DateTime('2015-01-12')));
Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new \DateTime('2015-01-15')));
Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new \DateTime('2015-01-18')));
