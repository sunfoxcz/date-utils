<?php declare(strict_types=1);

namespace SunfoxTests\DateUtils\Cases;

use DateTime as NativeDateTime;
use InvalidArgumentException;
use stdClass;
use Sunfox\DateUtils\DateIntervalFactory;
use Sunfox\DateUtils\DateTime;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class DateIntervalFactoryTest extends Tester\TestCase
{
	protected function getLoopArgs(): array
	{
		return [
			['2015-01-01', '2015-12-31', DateIntervalFactory::MONTH, 1, ['incomes' => [], 'expenses' => []], 12, '2015-01-01', '201501'],
			['2015-01-15', '2016-12-31', DateIntervalFactory::YEAR, 1, NULL, 2, '2015-01-15', '2015'],
			['2015-01-15', '2015-12-31', DateIntervalFactory::MONTH, 2, NULL, 6, '2015-01-15', '201501'],
			['2015-01-01', '2015-01-11', DateIntervalFactory::WEEK, 1, NULL, 2, '2015-01-01', '201501'],
			['2015-01-01', '2015-01-10', DateIntervalFactory::DAY, 1, NULL, 10, '2015-01-01', '20150101'],
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

		$array = DateIntervalFactory::create($start, $end, $interval, $count, $items);

		$expected = new stdClass;
		$expected->date = new DateTime($expectedDate);

		if ($items) {
			foreach ($items as $k => $v) {
				$expected->{$k} = $v;
			}
		}

		Assert::type('array', $array);
		Assert::type('stdClass', $array[$intervalKey]);
		Assert::type(DateTime::class, $array[$intervalKey]->date);
		Assert::equal($expected, $array[$intervalKey]);
		Assert::count($expectedCount, $array);
	}

	/**
	 * @throws InvalidArgumentException Unsupported interval
	 */
	public function testInvalidInterval(): void
	{
		$start = new NativeDateTime('2015-01-01');
		$end = new NativeDateTime('2015-12-31');

		DateIntervalFactory::create($start, $end, 'quarter', 1);
	}
}

(new DateIntervalFactoryTest)->run();
