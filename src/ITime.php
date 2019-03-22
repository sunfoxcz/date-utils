<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

interface ITime
{
	public const DEFAULT_FORMAT = 'H:i:s';
	public const DEFAULT_ROUNDING = 2;

	public function getSeconds(): int;

	public function getMinutes(int $rounding = self::DEFAULT_ROUNDING): float;

	public function getHours(int $rounding = self::DEFAULT_ROUNDING): float;

	public function getTime(string $format = self::DEFAULT_FORMAT): string;
}
