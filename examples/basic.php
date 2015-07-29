<?php

require __DIR__ . '/../vendor/autoload.php';


use Sunfox\DateUtils\DateTime;

$start = new \DateTime('2015-01-01');
$end = new \DateTime('2015-12-31');

$months = DateTime::createInterval(
	$start, $end, 'month', 1, [
		'incomes' => [],
		'expenses' => [],
	]
);

echo "------------------------------------------------------------\n";
echo "RESULT:\n";
echo "------------------------------------------------------------\n";
foreach ($months as $k => $month)
{
	echo "{$k}: " . $month->date->format('Y-m-d') . "\n";
}
