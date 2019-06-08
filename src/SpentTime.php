<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use InvalidArgumentException;
use Nette\Utils\Strings;

class SpentTime
{
	/**
	 * @var int
	 */
	private $hours = 0;

	/**
	 * @var int
	 */
	private $minutes = 0;

	/**
	 * @var int
	 */
	private $seconds = 0;

	public function __construct(?string $time = NULL)
	{
		if ($time === NULL) {
			return;
		}

		if (!$match = Strings::match($time, '~^([0-9]{2}):([0-9]{2}):([0-9]{2})$~')) {
			throw new InvalidArgumentException("Bad time format: '{$time}'.");
		}

		$this->hours = (int) $match[1];
		$this->minutes = (int) $match[2];
		$this->seconds = (int) $match[3];
	}

	public function __toString(): string
	{
		return Strings::padLeft((string) $this->hours, 2, '0') . ':' .
			Strings::padLeft((string) $this->minutes, 2, '0') . ':' .
			Strings::padLeft((string) $this->seconds, 2, '0');
	}

	public function isZero(): bool
	{
		return $this->hours === 0 && $this->minutes === 0 && $this->seconds === 0;
	}

	public function getHours(): int
	{
		return $this->hours;
	}

	public function getMinutes(): int
	{
		return $this->minutes;
	}

	public function getSeconds(): int
	{
		return $this->seconds;
	}

	public function getTotalHours(): float
	{
		return round($this->hours + $this->minutes / 60 + $this->seconds / 3600, 2);
	}

	public function getTotalMinutes(): float
	{
		return round($this->hours * 60 + $this->minutes + $this->seconds / 60, 2);
	}

	public function getTotalSeconds(): int
	{
		return $this->hours * 3600 + $this->minutes * 60 + $this->seconds;
	}

	public function getShortStringRepresentation(): string
	{
		if ($this->hours) {
			return $this->getTotalHours() . 'h';
		}

		return $this->getTotalMinutes() . 'm';
	}

	public function add(self $add): self
	{
		$sum = new self;
		$sum->hours = $this->hours + $add->getHours();
		$sum->minutes = $this->minutes + $add->getMinutes();
		$sum->seconds = $this->seconds + $add->getSeconds();

		if ($sum->seconds >= 60) {
			$sum->minutes += (int) floor($sum->seconds / 60);
			$sum->seconds %= 60;
		}

		if ($sum->minutes >= 60) {
			$sum->hours += (int) floor($sum->minutes / 60);
			$sum->minutes %= 60;
		}

		return $sum;
	}
}
