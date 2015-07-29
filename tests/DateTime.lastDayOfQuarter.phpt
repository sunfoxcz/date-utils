<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfQuarter(new \DateTime('2015-02-15')));
Assert::same('2015-03-31 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-02-15')));
Assert::same('2015-06-30 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-05-15')));
Assert::same('2015-09-30 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-08-15')));
Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-11-15')));
