<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use DateTime as NativeDateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class DateTime extends DateTimeImmutable implements \JsonSerializable
{
	/** minute in seconds */
	public const MINUTE = 60;

	/** hour in seconds */
	public const HOUR = 60 * self::MINUTE;

	/** day in seconds */
	public const DAY = 24 * self::HOUR;

	/** week in seconds */
	public const WEEK = 7 * self::DAY;

	/** average month in seconds */
	public const MONTH = 2629800;

	/** average year in seconds */
	public const YEAR = 31557600;

	/**
	 * Creates a DateTime object from a string, UNIX timestamp, or other DateTimeInterface object.
	 * @param  string|int|\DateTimeInterface  $time
	 * @return static
	 * @throws \Exception if the date and time are not valid.
	 */
	public static function from(string|int|\DateTimeInterface|null $time): static
	{
		if ($time instanceof \DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s.u'), $time->getTimezone());

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return (new static('@' . $time))->setTimezone(new DateTimeZone(date_default_timezone_get()));

		} else { // textual or null
			return new static((string) $time);
		}
	}

	/**
	 * Creates DateTime object.
	 * @return static
	 * @throws InvalidArgumentException if the date and time are not valid.
	 */
	public static function fromParts(
		int $year,
		int $month,
		int $day,
		int $hour = 0,
		int $minute = 0,
		float $second = 0.0
	): static {
		$s = sprintf('%04d-%02d-%02d %02d:%02d:%02.5F', $year, $month, $day, $hour, $minute, $second);
		if (
			!checkdate($month, $day, $year)
			|| $hour < 0
			|| $hour > 23
			|| $minute < 0
			|| $minute > 59
			|| $second < 0
			|| $second >= 60
		) {
			throw new InvalidArgumentException("Invalid date '$s'");
		}
		return new static($s);
	}

	/**
	 * Returns new DateTime object formatted according to the specified format.
	 * @param  string  $format  The format the $time parameter should be in
	 * @param  string  $datetime
	 * @param  DateTimeZone  $timezone (default timezone is used if null is passed)
	 * @return static|false
	 */
	public static function createFromFormat(string $format, string $datetime, ?DateTimeZone $timezone = null): DateTimeImmutable|false
	{
		if ($timezone === null) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}

		$date = parent::createFromFormat($format, $datetime, $timezone);
		return $date ? static::from($date) : false;
	}

	/**
	 * Get first day of year as DateTime instance
	 */
	public static function firstDayOfYear(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		return static::from(sprintf('%04d-01-01', $date->format('Y')));
	}

	/**
	 * Get last day of year as DateTime instance
	 */
	public static function lastDayOfYear(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		return static::from(sprintf('%04d-12-31', $date->format('Y')));
	}

	/**
	 * Get first day of quarter as DateTime instance
	 */
	public static function firstDayOfQuarter(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		$quarter = (int) ceil((int) $date->format('n') / 3);
		$format = sprintf('first day of %04d-%02d', $date->format('Y'), $quarter * 3 - 2);
		return static::from($format);
	}

	/**
	 * Get last day of quarter as DateTime instance
	 */
	public static function lastDayOfQuarter(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		$quarter = (int) ceil((int) $date->format('n') / 3);
		$format = sprintf('last day of %04d-%02d', $date->format('Y'), $quarter * 3);
		return static::from($format);
	}

	/**
	 * Get first day of month as DateTime instance
	 */
	public static function firstDayOfMonth(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-01'));
	}

	/**
	 * Get last day of month as DateTime instance
	 */
	public static function lastDayOfMonth(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-t'));
	}

	/**
	 * Get first day of week as DateTime instance
	 */
	public static function firstDayOfWeek(?DateTimeInterface $date = null): static
	{
		$date = self::checkDate($date);
		$dayOfWeek = (int) $date->format('N');

		if ($dayOfWeek === 1) {
			return static::from($date->format('Y-m-d'));
		}

		return static::from($date->format('Y-m-d') . ' -' . ($dayOfWeek - 1) . ' day');
	}

	final public function __construct(string $datetime = 'now', ?DateTimeZone $timezone = null)
	{
		parent::__construct($datetime, $timezone);
	}

	/**
	 * Get last day of week as DateTime instance
	 */
	public static function lastDayOfWeek(?DateTimeInterface $date = null): static
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

	/**
	 * Returns JSON representation in ISO 8601 (used by JavaScript).
	 */
	public function jsonSerialize(): string
	{
		return $this->format('c');
	}

	/**
	 * Returns the date and time in the format 'Y-m-d H:i:s'.
	 */
	public function __toString(): string
	{
		return $this->format('Y-m-d H:i:s');
	}
}
