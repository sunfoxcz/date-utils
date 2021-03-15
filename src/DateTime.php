<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use DateTime as NativeDateTime;
use DateTimeInterface;
use Nette\Utils\DateTime as NetteDateTime;

class DateTime extends NetteDateTime
{
	/**
	 * Get first day of year as DateTime instance
	 */
	public static function firstDayOfYear(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from(sprintf('%04d-01-01', $date->format('Y')));
	}

	/**
	 * Get last day of year as DateTime instance
	 */
	public static function lastDayOfYear(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from(sprintf('%04d-12-31', $date->format('Y')));
	}

	/**
	 * Get first day of quarter as DateTime instance
	 */
	public static function firstDayOfQuarter(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		$quarter = (int) ceil((int) $date->format('n') / 3);
		$format = sprintf('first day of %04d-%02d', $date->format('Y'), $quarter * 3 - 2);
		return static::from($format);
	}

	/**
	 * Get last day of quarter as DateTime instance
	 */
	public static function lastDayOfQuarter(?DateTimeInterface $date = NULL): self
	{
		$date = self::checkDate($date);
		$quarter = (int) ceil((int) $date->format('n') / 3);
		$format = sprintf('last day of %04d-%02d', $date->format('Y'), $quarter * 3);
		return static::from($format);
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

	private static function checkDate(?DateTimeInterface $date): DateTimeInterface
	{
		return $date ?: new NativeDateTime;
	}
}
