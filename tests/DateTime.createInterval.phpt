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
$end = new \DateTime('2015-12-31');
$months = DateTime::createInterval($start, $end, 'month', 2);

$expected = new Nette\Utils\ArrayHash;
$expected->date = new DateTime('2015-03-15');

Assert::type('array', $months);
Assert::type('Nette\Utils\ArrayHash', $months['201503']);
Assert::type('Sunfox\DateUtils\DateTime', $months['201503']->date);
Assert::equal($expected, $months['201503']);
Assert::count(6, $months);
