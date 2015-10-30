<?php

use Tester\Assert;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


$start = new \DateTime('2015-01-01');
$end = new \DateTime('2015-12-31');
$months = DateTime::createInterval(
	$start, $end, 'month', 1, [
		'incomes' => [],
		'expenses' => [],
	]
);

$expected = new Nette\Utils\ArrayHash;
$expected->date = new DateTime('2015-01-01');
$expected->incomes = [];
$expected->expenses = [];

Assert::type('array', $months);
Assert::type('Nette\Utils\ArrayHash', $months['201501']);
Assert::type('Sunfox\DateUtils\DateTime', $months['201501']->date);
Assert::equal($expected, $months['201501']);
Assert::count(12, $months);


$start = new \DateTime('2015-01-15');
$end = new \DateTime('2016-12-31');
$years = DateTime::createInterval($start, $end, 'year', 1);

$expected = new Nette\Utils\ArrayHash;
$expected->date = new DateTime('2016-01-15');

Assert::type('array', $years);
Assert::type('Nette\Utils\ArrayHash', $years['2016']);
Assert::type('Sunfox\DateUtils\DateTime', $years['2016']->date);
Assert::equal($expected, $years['2016']);
Assert::count(2, $years);


$start = new \DateTime('2015-01-15');
$end = new \DateTime('2015-12-31');
$months = DateTime::createInterval($start, $end, 'month', 2);

$expected = new Nette\Utils\ArrayHash;
$expected->date = new DateTime('2015-03-15');

Assert::type('array', $months);
Assert::type('Nette\Utils\ArrayHash', $months['201503']);
Assert::type('Sunfox\DateUtils\DateTime', $months['201503']->date);
Assert::equal($expected, $months['201503']);
Assert::count(6, $months);


$start = new \DateTime('2015-01-01');
$end = new \DateTime('2015-01-11');
$weeks = DateTime::createInterval($start, $end, 'week', 1);

$expected = new Nette\Utils\ArrayHash;
$expected->date = new DateTime('2015-01-01');

Assert::type('array', $weeks);
Assert::type('Nette\Utils\ArrayHash', $weeks['201501']);
Assert::type('Sunfox\DateUtils\DateTime', $weeks['201501']->date);
Assert::equal($expected, $weeks['201501']);
Assert::count(2, $weeks);


$start = new \DateTime('2015-01-01');
$end = new \DateTime('2015-01-10');
$days = DateTime::createInterval($start, $end, 'day', 1);

$expected = new Nette\Utils\ArrayHash;
$expected->date = new DateTime('2015-01-01');

Assert::type('array', $days);
Assert::type('Nette\Utils\ArrayHash', $days['20150101']);
Assert::type('Sunfox\DateUtils\DateTime', $days['20150101']->date);
Assert::equal($expected, $days['20150101']);
Assert::count(10, $days);
