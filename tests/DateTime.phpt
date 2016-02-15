<?php

namespace Sunfox\DateUtils\Tests;

use Tester;
use Tester\Assert;
use Nette\Utils\ArrayHash;
use Sunfox\DateUtils\DateTime;


require __DIR__ . '/bootstrap.php';


class DateTimeTest extends Tester\TestCase
{
	/**
	 * @return array
	 */
	protected function getLoopArgs()
	{
		return [
			['2015-01-01', '2015-12-31', 'month', 1, ['incomes' => [], 'expenses' => []], 12, '2015-01-01', '201501'],
			['2015-01-15', '2016-12-31', 'year', 1, NULL, 2, '2015-01-15', '2015'],
			['2015-01-15', '2015-12-31', 'month', 2, NULL, 6, '2015-01-15', '201501'],
			['2015-01-01', '2015-01-11', 'week', 1, NULL, 2, '2015-01-01', '201501'],
			['2015-01-01', '2015-01-10', 'day', 1, NULL, 10, '2015-01-01', '20150101'],
		];
	}

	/**
	 * @dataProvider getLoopArgs
	 */
	public function testValidInterval($dateFrom, $dateTo, $interval, $count, $items,
										$expectedCount, $expectedDate, $intervalKey)
	{
		$start = new \DateTime($dateFrom);
		$end = new \DateTime($dateTo);

		$interval = DateTime::createInterval(
			$start, $end, $interval, $count, $items
		);

		$expected = new ArrayHash;
		$expected->date = new DateTime($expectedDate);

		if ($items) {
			foreach ($items as $k => $v) {
				$expected->{$k} = $v;
			}
		}

		Assert::type('array', $interval);
		Assert::type('Nette\Utils\ArrayHash', $interval[$intervalKey]);
		Assert::type('Sunfox\DateUtils\DateTime', $interval[$intervalKey]->date);
		Assert::equal($expected, $interval[$intervalKey]);
		Assert::count($expectedCount, $interval);
	}

	/**
	 * @throws InvalidArgumentException Unsupported interval
	 */
	public function testInvalidInterval()
	{
		$start = new \DateTime('2015-01-01');
		$end = new \DateTime('2015-12-31');

		DateTime::createInterval(
			$start, $end, 'quarter', 1
		);
	}

	public function testFirstDayOfMonth()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfMonth(new \DateTime('2015-01-15')));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfMonth(new \DateTime('2015-01-15')));
		Assert::same('00:00:00', DateTime::firstDayOfMonth()->format('H:i:s'));
	}

	public function testLastDayOfMonth()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfMonth(new \DateTime('2015-01-15')));
		Assert::same('2015-01-31 00:00:00', (string) DateTime::lastDayOfMonth(new \DateTime('2015-01-15')));
		Assert::same('2015-02-28 00:00:00', (string) DateTime::lastDayOfMonth(new \DateTime('2015-02-15')));
		Assert::same('2015-04-30 00:00:00', (string) DateTime::lastDayOfMonth(new \DateTime('2015-04-15')));
		Assert::same('2016-02-29 00:00:00', (string) DateTime::lastDayOfMonth(new \DateTime('2016-02-15')));
		Assert::same('00:00:00', DateTime::lastDayOfMonth()->format('H:i:s'));
	}

	public function testFirstDayOfWeek()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfWeek(new \DateTime('2015-01-15')));
		Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new \DateTime('2015-01-12')));
		Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new \DateTime('2015-01-15')));
		Assert::same('2015-01-12 00:00:00', (string) DateTime::firstDayOfWeek(new \DateTime('2015-01-18')));
		Assert::same('00:00:00', DateTime::firstDayOfWeek()->format('H:i:s'));
	}

	public function testLastDayOfWeek()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfWeek(new \DateTime('2015-01-15')));
		Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new \DateTime('2015-01-12')));
		Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new \DateTime('2015-01-15')));
		Assert::same('2015-01-18 00:00:00', (string) DateTime::lastDayOfWeek(new \DateTime('2015-01-18')));
		Assert::same('00:00:00', DateTime::lastDayOfWeek()->format('H:i:s'));
	}

	public function testFirstDayOfQuarter()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfQuarter(new \DateTime('2015-02-15')));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-02-15')));
		Assert::same('2015-04-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-05-15')));
		Assert::same('2015-07-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-08-15')));
		Assert::same('2015-10-01 00:00:00', (string) DateTime::firstDayOfQuarter(new \DateTime('2015-11-15')));
		Assert::same('00:00:00', DateTime::firstDayOfQuarter()->format('H:i:s'));
	}

	public function testLastDayOfQuarter()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfQuarter(new \DateTime('2015-02-15')));
		Assert::same('2015-03-31 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-02-15')));
		Assert::same('2015-06-30 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-05-15')));
		Assert::same('2015-09-30 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-08-15')));
		Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfQuarter(new \DateTime('2015-11-15')));
		Assert::same('00:00:00', DateTime::lastDayOfQuarter()->format('H:i:s'));
	}

	public function testFirstDayOfYear()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::firstDayOfYear(2015));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfYear(2015));
		Assert::same('00:00:00', DateTime::firstDayOfYear()->format('H:i:s'));
	}

	public function testLastDayOfYear()
	{
		Assert::type('Sunfox\DateUtils\DateTime', DateTime::lastDayOfYear(2015));
		Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfYear(2015));
		Assert::same('00:00:00', DateTime::lastDayOfYear()->format('H:i:s'));
	}

}

(new DateTimeTest)->run();
