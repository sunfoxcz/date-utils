<?php

namespace Sunfox\DateUtils;

use Nette;


class DateTime extends Nette\Utils\DateTime
{
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
	 *
	 * @param int|NULL
	 * @return DateTime
	 */
	public static function firstDayOfYear($year = NULL)
	{
		$year = self::checkYear($year);
		return static::from("{$year}-01-01");
	}

	/**
	 * Get last day of year as DateTime instance
	 *
	 * @param int|NULL
	 * @return DateTime
	 */
	public static function lastDayOfYear($year = NULL)
	{
		$year = self::checkYear($year);
		return static::from("{$year}-12-31");
	}

	/**
	 * Get first day of quarter as DateTime instance
	 *
	 * @param \DateTime|NULL
	 * @return DateTime
	 */
	public static function firstDayOfQuarter(\DateTime $date = NULL)
	{
		$date = self::checkDate($date);

		return static::from($date->format(
			self::$quarters[ceil($date->format('n') / 3)]['start']
		));
	}

	/**
	 * Get last day of quarter as DateTime instance
	 *
	 * @param \DateTime|NULL
	 * @return DateTime
	 */
	public static function lastDayOfQuarter(\DateTime $date = NULL)
	{
		$date = self::checkDate($date);

		return static::from($date->format(
			self::$quarters[ceil($date->format('n') / 3)]['end']
		));
	}

	/**
	 * Get first day of month as DateTime instance
	 *
	 * @param \DateTime|NULL
	 * @return DateTime
	 */
	public static function firstDayOfMonth(\DateTime $date = NULL)
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-01'));
	}

	/**
	 * Get last day of month as DateTime instance
	 *
	 * @param \DateTime|NULL
	 * @return DateTime
	 */
	public static function lastDayOfMonth(\DateTime $date = NULL)
	{
		$date = self::checkDate($date);
		return static::from($date->format('Y-m-t'));
	}

	/**
	 * Get array of DateTime instances, starting by $dateFrom and ending before $dateTo.
	 * Each instance is incremented by $interval $count times compared to previous. $items
	 * are added to each instance if provided.
	 *
	 * @param \DateTime
	 * @param \DateTime
	 * @param string
	 * @param int
	 * @param array|NULL
	 * @return Nette\Utils\ArrayHash[]
	 */
	public static function createInterval(\DateTime $dateFrom, \DateTime $dateTo,
											$interval = 'month', $count = 1, array $items = NULL)
	{
		$currentDate = static::from($dateFrom);

		$result = [];
		while ($currentDate <= $dateTo)
		{
			$yearMonth = $currentDate->format('Ym');
			$result[$yearMonth] = new Nette\Utils\ArrayHash;
			$result[$yearMonth]->date = clone($currentDate);

			if ($items) {
				foreach ($items as $k => $v) {
					$result[$yearMonth]->{$k} = $v;
				}
			}

			$currentDate->modify("+{$count} {$interval}");
		}

		return $result;
	}

	/**
	 * @internal
	 */
	private static function checkYear($year)
	{
		return $year === NULL ? date('Y') : $year;
	}

	/**
	 * @internal
	 */
	private static function checkDate($date)
	{
		return $date === NULL ? new \DateTime : $date;
	}

}
