<?php declare(strict_types=1);

namespace Sunfox\DateUtils;

use DateTimeInterface;
use InvalidArgumentException;
use Nette\Utils\ArrayHash;

final class DateIntervalFactory
{
    public const YEAR = 'year';
    public const MONTH = 'month';
    public const WEEK = 'week';
    public const DAY = 'day';

    /**
     * Create array of DateTime instances, starting by $dateFrom and ending before $dateTo.
     * Each instance is incremented by $interval $count times compared to previous.
     * $items are added to each instance if provided.
     *
     * @param array<string, mixed>|null $items
     *
     * @return ArrayHash[]
     */
    public static function create(
        DateTimeInterface $dateFrom,
        DateTimeInterface $dateTo,
        string $interval = self::MONTH,
        int $count = 1,
        ?array $items = NULL
    ): array {
        $currentDate = DateTime::from($dateFrom);

        $result = [];
        while ($currentDate <= $dateTo) {
            $intervalKey = self::getIntervalKey($currentDate, $interval);
            $result[$intervalKey] = new ArrayHash;
            $result[$intervalKey]->date = $currentDate;

            if ($items) {
                foreach ($items as $k => $v) {
                    $result[$intervalKey]->{$k} = $v;
                }
            }

            $currentDate = $currentDate->modify("+{$count} {$interval}");
        }

        return $result;
    }

    private static function getIntervalKey(DateTime $currentDate, string $internal): string
    {
        switch ($internal) {
            case self::YEAR:
                return $currentDate->format('Y');
            case self::MONTH:
                return $currentDate->format('Ym');
            case self::WEEK:
                return $currentDate->format('YW');
            case self::DAY:
                return $currentDate->format('Ymd');
        }

        throw new InvalidArgumentException('Unsupported interval');
    }
}
