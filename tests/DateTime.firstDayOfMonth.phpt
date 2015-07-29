<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfMonth(new \DateTime('2015-01-15')));
Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfMonth(new \DateTime('2015-01-15')));
Assert::same(date('Y-m-01 00:00:00'), (string) DateTime::firstDayOfMonth());
