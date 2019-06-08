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
		$this->seconds = $this->parseLetterExpression($time) ?: $this->parseTimeExpression($time);
		if (!$this->seconds) {
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

	private function parseLetterExpression(string $time): int
	{
		if (preg_match(
			'/^(?:(?<h>\d+(?:[.,]\d+)?)(?:h|$))?(?:(?<m>\d+(?:[.,]\d+)?)(?:m|$))?(?:(?<s>\d+(?:[.,]\d+)?)(?:s|$))?$/i',
			trim($time),
			$m
		)) {
			return (int) (
				(!empty($m['h']) ? (float) str_replace(',', '.', $m['h']) * 60 * 60 : 0) +
				(!empty($m['m']) ? (float) str_replace(',', '.', $m['m']) * 60 : 0) +
				(!empty($m['s']) ? (float) str_replace(',', '.', $m['s']) : 0)
			);
		}

		return 0;
	}

	private function parseTimeExpression(string $time): int
	{
		if (preg_match('/^(\d+):(\d+)(?::(\d+))?$/', trim($time), $m)) {
			return (int) (
				($m[1] * 60 * 60) + ($m[2] * 60) + (!empty($m[3]) ? $m[3] : 0)
			);
		}

		return 0;
	}
}
