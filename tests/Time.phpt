<?php declare(strict_types=1);

namespace Sunfox\DateUtils\Tests;

use InvalidArgumentException;
use Sunfox\DateUtils\Time;
use Tester;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class TimeTest extends Tester\TestCase
{
	protected function getLoopArgs(): array
	{
		return [
			['12', 43200, 720.0, 12.0, '12:00:00', '12h', 'G\h'],
			['12:24', 44640, 744.0, 12.4, '12:24:00', '12h24m', 'G\hi\m'],
			['12:24:15', 44655, 744.25, 12.4, '12:24:15', '12h24m15s', 'G\hi\ms\s'],
			['2:24:15', 8655, 144.25, 2.4, '02:24:15', '2h24m15s', 'G\hi\ms\s'],
			['2:4:5', 7445, 124.08, 2.07, '02:04:05', '2h04m05s', 'G\hi\ms\s'],
			['1', 3600, 60.0, 1.0, '01:00:00', '1h', 'G\h'],
			['1h', 3600, 60.0, 1.0, '01:00:00', '1h', 'G\h'],
			['1.5', 5400, 90.0, 1.5, '01:30:00', '1h30m', 'G\hi\m'],
			['1.5h', 5400, 90.0, 1.5, '01:30:00', '1h30m', 'G\hi\m'],
			['24.5m', 1470, 24.5, 0.41, '00:24:30', '24m30s', 'i\ms\s'],
			['30s', 30, 0.5, 0.01, '00:00:30', '30s', 's\s'],
			['12h24', 44640, 744.0, 12.4, '12:24:00', '12h24m', 'G\hi\m'],
			['12h24m', 44640, 744.0, 12.4, '12:24:00', '12h24m', 'G\hi\m'],
			['12h24m15', 44655, 744.25, 12.4, '12:24:15', '12h24m15s', 'G\hi\ms\s'],
			['12h24m15s', 44655, 744.25, 12.4, '12:24:15', '12h24m15s', 'G\hi\ms\s'],
			['12h0m0s', 43200, 720.0, 12.0, '12:00:00', '12h00m00s', 'G\hi\ms\s'],
			['12h0s', 43200, 720.0, 12.0, '12:00:00', '12h00m00s', 'G\hi\ms\s'],
			['12h15s', 43215, 720.25, 12.0, '12:00:15', '12h00m15s', 'G\hi\ms\s'],
		];
	}

	/**
	 * @dataProvider getLoopArgs
	 */
	public function testValid(
		string $value,
		int $seconds,
		float $minutes,
		float $hours,
		string $colonString,
		string $unitString,
		string $format
	): void {
		$time = new Time($value);

		Assert::same($seconds, $time->getSeconds());
		Assert::same($minutes, $time->getMinutes());
		Assert::same($hours, $time->getHours());
		Assert::same($colonString, $time->getTime());
		Assert::same($unitString, $time->getTime($format));
	}

	/**
	 * @throws InvalidArgumentException Cannot parse time value
	 */
	public function testInvalid(): void
	{
		new Time('aaa');
	}
}

(new TimeTest)->run();
