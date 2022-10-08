<?php declare(strict_types=1);

namespace SunfoxTests\DateUtils\Cases;

use DateTime as NativeDateTime;
use Sunfox\DateUtils\DateTime;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class DateTimeTest extends Tester\TestCase
{
	public function testFrom(): void
	{
		Assert::same('1978-01-23 11:40:00', (string) DateTime::from(254400000));
		Assert::same('1978-01-23 11:40:00', (string) (new DateTime)->setTimestamp(254400000));
		Assert::same(254400000, DateTime::from(254400000)->getTimestamp());

		Assert::same(time() + 60, (int) DateTime::from(60)->format('U'));
		Assert::same(2544000000, DateTime::from(2544000000)->getTimestamp());
		if (version_compare(PHP_VERSION, '8.1.0') >= 0) {
			Assert::same('2050-08-13 12:40:00', (string)DateTime::from(2544000000));
			Assert::same('2050-08-13 12:40:00', (string)(new DateTime)->setTimestamp(2544000000));
		} else {
			Assert::same('2050-08-13 11:40:00', (string)DateTime::from(2544000000));
			Assert::same('2050-08-13 11:40:00', (string)(new DateTime)->setTimestamp(2544000000));
		}

		Assert::same('1978-05-05 00:00:00', (string) DateTime::from('1978-05-05'));

		Assert::same((new \Datetime)->format('Y-m-d H:i:s'), (string) DateTime::from(null));

		Assert::type(DateTime::class, DateTime::from(new \DateTime('1978-05-05')));

		Assert::same('1978-05-05 12:00:00.123450', DateTime::from(new DateTime('1978-05-05 12:00:00.12345'))->format('Y-m-d H:i:s.u'));
	}

	public function testFromParts(): void
	{
		Assert::same('0001-12-09 00:00:00.000000', DateTime::fromParts(1, 12, 9)->format('Y-m-d H:i:s.u'));
		Assert::same('0085-12-09 00:00:00.000000', DateTime::fromParts(85, 12, 9)->format('Y-m-d H:i:s.u'));
		Assert::same('1985-01-01 00:00:00.000000', DateTime::fromParts(1985, 1, 1)->format('Y-m-d H:i:s.u'));
		Assert::same('1985-12-19 00:00:00.000000', DateTime::fromParts(1985, 12, 19)->format('Y-m-d H:i:s.u'));
		Assert::same('1985-12-09 01:02:00.000000', DateTime::fromParts(1985, 12, 9, 1, 2)->format('Y-m-d H:i:s.u'));
		Assert::same('1985-12-09 01:02:03.000000', DateTime::fromParts(1985, 12, 9, 1, 2, 3)->format('Y-m-d H:i:s.u'));
		Assert::same('1985-12-09 11:22:33.000000', DateTime::fromParts(1985, 12, 9, 11, 22, 33)->format('Y-m-d H:i:s.u'));
		Assert::same('1985-12-09 11:22:59.123000', DateTime::fromParts(1985, 12, 9, 11, 22, 59.123)->format('Y-m-d H:i:s.u'));

		Assert::exception(
			fn() => DateTime::fromParts(1985, 2, 29),
			\InvalidArgumentException::class,
			"Invalid date '1985-02-29 00:00:0.00000'"
		);

		Assert::exception(
			// year must be at least 1 due to limitation of checkdate()
			fn() => DateTime::fromParts(0, 12, 9),
			\InvalidArgumentException::class
		);

		Assert::exception(fn() => DateTime::fromParts(1985, 0, 9), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 13, 9), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 0), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 32), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 9, -1), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 9, 60), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 9, 0, -1), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 9, 0, 60), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 9, 0, 0, -1), \InvalidArgumentException::class);
		Assert::exception(fn() => DateTime::fromParts(1985, 12, 9, 0, 0, 60), \InvalidArgumentException::class);
	}

	public function testCreateFromFormat(): void
	{
		Assert::type(DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00'));
		Assert::type(DateTime::class, DateTime::createFromFormat('Y-m-d H:i:s', '2050-08-13 11:40:00', new \DateTimeZone('Europe/Prague')));

		Assert::same('2050-08-13 11:40:00.123450', DateTime::createFromFormat('Y-m-d H:i:s.u', '2050-08-13 11:40:00.12345')->format('Y-m-d H:i:s.u'));

		Assert::same('Europe/Prague', DateTime::createFromFormat('Y', '2050')->getTimezone()->getName());
		Assert::same('Europe/Bratislava', DateTime::createFromFormat('Y', '2050', new \DateTimeZone('Europe/Bratislava'))->getTimezone()->getName());

		Assert::false(DateTime::createFromFormat('Y-m-d', '2014-10'));
	}

	public function testJSON(): void
	{
		Assert::same('"1978-01-23T11:40:00+01:00"', json_encode(DateTime::from(254400000)));
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
		Assert::type(DateTime::class, DateTime::firstDayOfYear(new NativeDateTime('2015-02-15')));
		Assert::same('2015-01-01 00:00:00', (string) DateTime::firstDayOfYear(new NativeDateTime('2015-02-15')));
		Assert::same('00:00:00', DateTime::firstDayOfYear()->format('H:i:s'));
	}

	public function testLastDayOfYear(): void
	{
		Assert::type(DateTime::class, DateTime::lastDayOfYear(new NativeDateTime('2015-02-15')));
		Assert::same('2015-12-31 00:00:00', (string) DateTime::lastDayOfYear(new NativeDateTime('2015-02-15')));
		Assert::same('00:00:00', DateTime::lastDayOfYear()->format('H:i:s'));
	}
}

(new DateTimeTest)->run();
