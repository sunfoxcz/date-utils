<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfQuarter(new \DateTime('2015-02-15')));
Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-02-15')));
Assert::same('2015-04-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-05-15')));
Assert::same('2015-07-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-08-15')));
Assert::same('2015-10-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-11-15')));
