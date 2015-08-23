<?php

namespace Sunfox\DateUtils;


interface ITime
{
	const DEFAULT_FORMAT = 'H:i:s';
	const DEFAULT_ROUNDING = 2;


	/**
	 * @return int
	 */
	public function getSeconds();

	/**
	 * @return float
	 */
	public function getMinutes($rounding = self::DEFAULT_ROUNDING);

	/**
	 * @return float
	 */
	public function getHours($rounding = self::DEFAULT_ROUNDING);

	/**
	 * @param string
	 * @return string
	 */
	public function getTime($format = self::DEFAULT_FORMAT);

}
