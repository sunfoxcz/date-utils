<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use DateTime as NativeDateTime;
use InvalidArgumentException;

final class Time implements ITime
{
	/**
	 * @var int
	 */
	private $seconds = 0;

	/**
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $time)
	{
		if (preg_match(
			'/^(?:(?<h>\d+(?:[.,]\d+)?)(?:h|$))?(?:(?<m>\d+(?:[.,]\d+)?)(?:m|$))?(?:(?<s>\d+(?:[.,]\d+)?)(?:s|$))?$/i',
			trim($time),
			$m
		)) {
			$this->seconds = (int) (
				(!empty($m['h']) ? str_replace(',', '.', $m['h']) * 60 * 60 : 0) +
				(!empty($m['m']) ? str_replace(',', '.', $m['m']) * 60 : 0) +
				(!empty($m['s']) ? str_replace(',', '.', $m['s']) : 0)
			);
		} elseif (preg_match('/^(\d+):(\d+)(?::(\d+))?$/', trim($time), $m)) {
			$this->seconds = ($m[1] * 60 * 60) + ($m[2] * 60) + (!empty($m[3]) ? $m[3] : 0);
		} else {
			throw new InvalidArgumentException('Cannot parse time value');
		}
	}

	public function getSeconds(): int
	{
		return $this->seconds;
	}

	public function getMinutes(int $rounding = self::DEFAULT_ROUNDING): float
	{
		return round($this->seconds / 60, $rounding);
	}

	public function getHours(int $rounding = self::DEFAULT_ROUNDING): float
	{
		return round($this->seconds / 60 / 60, $rounding);
	}

	public function getTime(string $format = self::DEFAULT_FORMAT): string
	{
		return (new NativeDateTime("@$this->seconds"))->format($format);
	}
}
