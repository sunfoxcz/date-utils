<?php declare(strict_types=1);

namespace Sunfox\DateUtils\Tests;

use DateTime as NativeDateTime;
use InvalidArgumentException;
use Nette\Utils\ArrayHash;
use Sunfox\DateUtils\DateTime;
use Tester;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

final class DateTimeTest extends Tester\TestCase
{
	protected function getLoopArgs(): array
	{
		return [
			['2015-01-01', '2015-12-31', DateTime::INTERVAL_MONTH, 1, ['incomes' => [], 'expenses' => []], 12, '2015-01-01', '201501'],
			['2015-01-15', '2016-12-31', DateTime::INTERVAL_YEAR, 1, NULL, 2, '2015-01-15', '2015'],
			['2015-01-15', '2015-12-31', DateTime::INTERVAL_MONTH, 2, NULL, 6, '2015-01-15', '201501'],
			['2015-01-01', '2015-01-11', DateTime::INTERVAL_WEEK, 1, NULL, 2, '2015-01-01', '201501'],
			['2015-01-01', '2015-01-10', DateTime::INTERVAL_DAY, 1, NULL, 10, '2015-01-01', '20150101'],
		];
	}

	/**
	 * @dataProvider getLoopArgs
	 */
	public function testValidInterval(
		string $dateFrom,
		string $dateTo,
		string $interval,
		int $count,
		?array $items,
		int $expectedCount,
		string $expectedDate,
		string $intervalKey
	): void {
		$start = new NativeDateTime($dateFrom);
		$end = new NativeDateTime($dateTo);

		$interval = DateTime::createInterval($start, $end, $interval, $count, $items);

		$expected = new ArrayHash;
		$expected->date = new DateTime($expectedDate);

		if ($items) {
			foreach ($items as $k => $v) {
				$expected->{$k} = $v;
			}
		}

		Assert::type('array', $interval);
		Assert::type('Nette\Utils\ArrayHash', $interval[$intervalKey]);
		Assert::type(DateTime::class, $interval[$intervalKey]->date);
		Assert::equal($expected, $interval[$intervalKey]);
		Assert::count($expectedCount, $interval);
	}

	/**
	 * @throws InvalidArgumentException Unsupported interval
	 */
	public function testInvalidInterval(): void
	{
		$start = new NativeDateTime('2015-01-01');
		$end = new NativeDateTime('2015-12-31');

		DateTime::createInterval($start, $end, 'quarter', 1);
	}

	public function testFirstDayOfMonth(): void
	{
		Assert::type(DateTime::class, DateTime::firstDayOfMonth(new NativeDateTime('2015-01-15')));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfMonth(new NativeDateTime('2015-01-15')));
		Assert::same('00:00:00', DateTime::firstDayOfMonth()->format('H:i:s'));
	}

	public function testLastDayOfMonth(): void
	{
		Assert::type(DateTime::class, DateTime::lastDayOfMonth(new NativeDateTime('2015-01-15')));
		Assert::same('2015-01-31 00:00:00', (string) DateTime::lastDayOfMonth(new NativeDateTime('2015-01-15')));
		Assert::same('2015-02-28 00:00:00', (string) DateTime::lastDayOfMonth(new NativeDateTime('2015-02-15')));
		Assert::same('2015-04-30 00:00:00', (string) DateTime::lastDayOfMonth(new NativeDateTime('2015-04-15')));
		Assert::same('2016-02-29 00:00:00', (string) DateTime::lastDayOfMonth(new NativeDateTime('2016-02-15')));
		Assert::same('00:00:00', DateTime::lastDayOfMonth()->format('H:i:s'));
	}

	public function testFirstDayOfWeek(): void
	{
		Assert::type(DateTime::class, DateTime::firstDayOfWeek(new NativeDateTime('2015-01-15')));
		Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new NativeDateTime('2015-01-12')));
		Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new NativeDateTime('2015-01-15')));
		Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new NativeDateTime('2015-01-18')));
		Assert::same('00:00:00', DateTime::firstDayOfWeek()->format('H:i:s'));
	}

	public function testLastDayOfWeek(): void
	{
		Assert::type(DateTime::class, DateTime::lastDayOfWeek(new NativeDateTime('2015-01-15')));
		Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new NativeDateTime('2015-01-12')));
		Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new NativeDateTime('2015-01-15')));
		Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new NativeDateTime('2015-01-18')));
		Assert::same('00:00:00', DateTime::lastDayOfWeek()->format('H:i:s'));
	}

	public function testFirstDayOfQuarter(): void
	{
		Assert::type(DateTime::class, DateTime::firstDayOfQuarter(new NativeDateTime('2015-02-15')));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfQuarter(new NativeDateTime('2015-02-15')));
		Assert::same('2015-04-01 00:00:00', (string) DateTime::firstDayOfQuarter(new NativeDateTime('2015-05-15')));
		Assert::same('2015-07-01 00:00:00', (string) DateTime::firstDayOfQuarter(new NativeDateTime('2015-08-15')));
		Assert::same('2015-10-01 00:00:00', (string) DateTime::firstDayOfQuarter(new NativeDateTime('2015-11-15')));
		Assert::same('00:00:00', DateTime::firstDayOfQuarter()->format('H:i:s'));
	}

	public function testLastDayOfQuarter(): void
	{
		Assert::type(DateTime::class, DateTime::lastDayOfQuarter(new NativeDateTime('2015-02-15')));
		Assert::same('2015-03-31 00:00:00', (string) DateTime::lastDayOfQuarter(new NativeDateTime('2015-02-15')));
		Assert::same('2015-06-30 00:00:00', (string) DateTime::lastDayOfQuarter(new NativeDateTime('2015-05-15')));
		Assert::same('2015-09-30 00:00:00', (string) DateTime::lastDayOfQuarter(new NativeDateTime('2015-08-15')));
		Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfQuarter(new NativeDateTime('2015-11-15')));
		Assert::same('00:00:00', DateTime::lastDayOfQuarter()->format('H:i:s'));
	}

	public function testFirstDayOfYear(): void
	{
		Assert::type(DateTime::class, DateTime::firstDayOfYear(2015));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfYear(2015));
		Assert::same('00:00:00', DateTime::firstDayOfYear()->format('H:i:s'));
	}

	public function testLastDayOfYear(): void
	{
		Assert::type(DateTime::class, DateTime::lastDayOfYear(2015));
		Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfYear(2015));
		Assert::same('00:00:00', DateTime::lastDayOfYear()->format('H:i:s'));
	}
}

(new DateTimeTest)->run();
