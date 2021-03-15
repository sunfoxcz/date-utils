# DateUtils library

<p align=center>
    <a href="https://github.com/sunfoxcz/date-utils/actions"><img src="https://badgen.net/github/checks/sunfoxcz/date-utils/master?cache=300"></a>
    <a href="https://coveralls.io/github/sunfoxcz/date-utils"><img src="https://badgen.net/coveralls/c/github/sunfoxcz/date-utils?cache=300"></a>
    <a href="https://github.com/sunfoxcz/date-utils"><img src="https://badgen.net/github/license/sunfoxcz/date-utils"></a>
</p>
<p align=center>
    <a href="https://packagist.org/packages/sunfoxcz/date-utils"><img src="https://badgen.net/packagist/dm/sunfoxcz/date-utils"></a>
    <a href="https://packagist.org/packages/sunfoxcz/date-utils"><img src="https://badgen.net/packagist/v/sunfoxcz/date-utils"></a>
    <a href="https://packagist.org/packages/sunfoxcz/date-utils"><img src="https://badgen.net/packagist/php/sunfoxcz/date-utils"></a>
</p>

Library for easier working with date intervals.

## Installation

```bash
composer require sunfoxcz/date-utils
```

## Usage

### DateIntervalFactory

```php
<?php declare(strict_types=1);

use Sunfox\DateUtils\DateTime;
use Sunfox\DateUtils\DateIntervalFactory;

$start = new DateTime('2015-01-01');
$end = new DateTime('2015-12-31');

$months = DateIntervalFactory::create($start, $end, DateIntervalFactory::MONTH, 1, [
	'incomes' => [],
	'expenses' => [],
]);

foreach ($months as $k => $month) {
	echo "{$k}: " . $month->date->format('Y-m-d') . PHP_EOL;
}
```

### SpentTime

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
