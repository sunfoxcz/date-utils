# DateUtils library

[![Build Status](https://travis-ci.org/sunfoxcz/date-utils.svg?branch=master)](https://travis-ci.org/sunfoxcz/date-utils)
[![Code Coverage](https://scrutinizer-ci.com/g/sunfoxcz/date-utils/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sunfoxcz/date-utils/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sunfoxcz/date-utils/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sunfoxcz/date-utils/?branch=master)

Library for easier working with date intervals.

## Installation

```bash
composer require sunfoxcz/date-utils
```

## Usage

### DateTime::createInterval()

```php
<?php declare(strict_types=1);

use Sunfox\DateUtils\DateTime;

$start = new DateTime('2015-01-01');
$end = new DateTime('2015-12-31');

$months = DateTime::createInterval($start, $end, 'month', 1, [
	'incomes' => [],
	'expenses' => [],
]);

foreach ($months as $k => $month) {
	echo "{$k}: " . $month->date->format('Y-m-d') . PHP_EOL;
}
```

### SpentTime()

```php
<?php declare(strict_types=1);

use Sunfox\DateUtils\SpentTime;

$spentTime = new SpentTime('1h30m');

echo (string) $spentTime; // 01:30:00
echo $spentTime->getTotalHours(); // 1,5
echo $spentTime->getTotalMinutes(); // 90
echo $spentTime->getTotalSeconds(); // 5400
```

For more usages Look into tests directory.
