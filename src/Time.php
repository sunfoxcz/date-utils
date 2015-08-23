<?php

namespace Sunfox\DateUtils;


class Time implements ITime
{
	/** @var int */
	protected $seconds = 0;


	/**
	 * @param mixed
	 */
	public function __construct($time)
	{
		if (preg_match('/^(?:(?<h>\d+(?:[.,]\d+)?)(?:h|$))?(?:(?<m>\d+(?:[.,]\d+)?)(?:m|$))?(?:(?<s>\d+(?:[.,]\d+)?)(?:s|$))?$/i', trim((string) $time), $m)) {
			$this->seconds = (int) (
				(str_replace(',', '.', $m['h']) * 60 * 60) +
				(isset($m['m']) ? str_replace(',', '.', $m['m']) * 60 : 0) +
				(isset($m['s']) ? str_replace(',', '.', $m['s']) : 0)
			);
		} elseif (preg_match('/^(\d+):(\d+)(?::(\d+))?$/', trim($time), $m)) {
			$this->seconds = ($m[1] * 60 * 60) + ($m[2] * 60) + (isset($m[3]) ? $m[3] : 0);
		} else {
			throw new \InvalidArgumentException('Cannot parse time value');
		}
	}

	/**
	 * @return int
	 */
	public function getSeconds()
	{
		return $this->seconds;
	}

	/**
	 * @return float
	 */
	public function getMinutes($rounding = self::DEFAULT_ROUNDING)
	{
		return round($this->seconds / 60, $rounding);
	}

	/**
	 * @return float
	 */
	public function getHours($rounding = self::DEFAULT_ROUNDING)
	{
		return round($this->seconds / 60 / 60, $rounding);
	}

	/**
	 * @param string
	 * @return string
	 */
	public function getTime($format = self::DEFAULT_FORMAT)
	{
		return (new \DateTime("@$this->seconds"))->format($format);
	}

}
