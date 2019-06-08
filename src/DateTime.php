<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use DateTime as NativeDateTime;
use DateTimeInterface;
use Nette\Utils\DateTime as NetteDateTime;

class DateTime extends NetteDateTime
{
	/**
	 * @var array<array>
	 */
	public static $quarters = [
		1 => [
			'start' => 'Y-01-01',
			'end' => 'Y-03-31',
		],
		2 => [
			'start' => 'Y-04-01',
			'end' => 'Y-06-30',
		],
		3 => [
			'start' => 'Y-07-01',
			'end' => 'Y-09-30',
		],
		4 => [
			'start' => 'Y-10-01',
			'end' => 'Y-12-31',
		],
	];

	/**
	 * Get first day of year as DateTime instance
	 */
	public static function firstDayOfYear(?int $year = NULL): self
	{
		$year = self::checkYear($year);
		return static::from("{$year}-01-01");
	}

	/**
	 * Get last day of year as DateTime instance
	 */
	public static function lastDayOfYear(?int $year = NULL): self
	{
		$year = self::checkYear($year);
		return static::from("{$year}-12-31");
	}

	/**
	 * Get first day of quarter as DateTime instance
	 */
	public static function firstDayOfQuarter(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format(self::$quarters[(int) ceil((int) $date->format('n') / 3)]['start']));
	}

	/**
	 * Get last day of quarter as DateTime instance
	 */
	public static function lastDayOfQuarter(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format(self::$quarters[(int) ceil((int) $date->format('n') / 3)]['end']));
	}

	/**
	 * Get first day of month as DateTime instance
	 */
	public static function firstDayOfMonth(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-01'));
	}

	/**
	 * Get last day of month as DateTime instance
	 */
	public static function lastDayOfMonth(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-t'));
	}

	/**
	 * Get first day of week as DateTime instance
	 */
	public static function firstDayOfWeek(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		$dayOfWeek = (int) $date->format('N');

		if ($dayOfWeek === 1) {
			return static::from($date->format('Y-m-d'));
		}

		return static::from($date->format('Y-m-d') . ' -' . ($dayOfWeek - 1) . ' day');
	}

	/**
	 * Get last day of week as DateTime instance
	 */
	public static function lastDayOfWeek(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		$dayOfWeek = (int) $date->format('N');

		if ($dayOfWeek === 7) {
			return static::from($date->format('Y-m-d'));
		}

		return static::from($date->format('Y-m-d') . ' +' . (7 - $dayOfWeek) . ' day');
	}

	private static function checkYear(?int $year): int
	{
		return $year ?: (int) date('Y');
	}

	private static function checkDate(?DateTimeInterface $date): DateTimeInterface
	{
		return $date ?: new NativeDateTime;
	}
}
