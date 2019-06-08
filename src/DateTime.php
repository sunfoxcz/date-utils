<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use DateTime as NativeDateTime;
use InvalidArgumentException;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime as NetteDateTime;

class DateTime extends NetteDateTime
{
	public const INTERVAL_YEAR = 'year';
	public const INTERVAL_MONTH = 'month';
	public const INTERVAL_WEEK = 'week';
	public const INTERVAL_DAY = 'day';

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
	public static function firstDayOfQuarter(?NativeDateTime $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format(self::$quarters[(int) ceil((int) $date->format('n') / 3)]['start']));
	}

	/**
	 * Get last day of quarter as DateTime instance
	 */
	public static function lastDayOfQuarter(?NativeDateTime $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format(self::$quarters[(int) ceil((int) $date->format('n') / 3)]['end']));
	}

	/**
	 * Get first day of month as DateTime instance
	 */
	public static function firstDayOfMonth(?NativeDateTime $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-01'));
	}

	/**
	 * Get last day of month as DateTime instance
	 */
	public static function lastDayOfMonth(?NativeDateTime $date = NULL): self
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-t'));
	}

	/**
	 * Get first day of week as DateTime instance
	 */
	public static function firstDayOfWeek(?NativeDateTime $date = NULL): self
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
	public static function lastDayOfWeek(?NativeDateTime $date = NULL): self
	{
		$date = self::checkDate($date);
		$dayOfWeek = (int) $date->format('N');

		if ($dayOfWeek === 7) {
			return static::from($date->format('Y-m-d'));
		}

		return static::from($date->format('Y-m-d') . ' +' . (7 - $dayOfWeek) . ' day');
	}

	/**
	 * Get array of DateTime instances, starting by $dateFrom and ending before $dateTo.
	 * Each instance is incremented by $interval $count times compared to previous. $items
	 * are added to each instance if provided.
	 *
	 * @return ArrayHash[]
	 */
	public static function createInterval(
		NativeDateTime $dateFrom,
		NativeDateTime $dateTo,
		string $interval = 'month',
		int $count = 1,
		?array $items = NULL
	): array {
		$currentDate = static::from($dateFrom);

		$result = [];
		while ($currentDate <= $dateTo) {
			$intervalKey = self::getIntervalKey($currentDate, $interval);
			$result[$intervalKey] = new ArrayHash;
			$result[$intervalKey]->date = clone $currentDate;

			if ($items) {
				foreach ($items as $k => $v) {
					$result[$intervalKey]->{$k} = $v;
				}
			}

			$currentDate->modify("+{$count} {$interval}");
		}

		return $result;
	}

	private static function checkYear(?int $year): int
	{
		return $year ?: (int) date('Y');
	}

	private static function checkDate(?NativeDateTime $date): NativeDateTime
	{
		return $date ?: new NativeDateTime;
	}

	private static function getIntervalKey(self $currentDate, string $internal): string
	{
		switch ($internal) {
			case self::INTERVAL_YEAR:
				return $currentDate->format('Y');
			case self::INTERVAL_MONTH:
				return $currentDate->format('Ym');
			case self::INTERVAL_WEEK:
				return $currentDate->format('YW');
			case self::INTERVAL_DAY:
				return $currentDate->format('Ymd');
		}

		throw new InvalidArgumentException('Unsupported interval');
	}
}
