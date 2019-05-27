<?php declare(strict_types=1);

namespace Sunfox\DateUtils\Tests;

use Sunfox\DateUtils\SpentTime;
use Tester;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

final class SpentTimeTest extends Tester\TestCase
{
    protected function getLoopArgs(): array
    {
        return [
            [NULL, 0, 0, 0, 0.0, 0.0, 0, '0m'],
            ['00:00:00', 0, 0, 0, 0.0, 0.0, 0, '0m'],
            ['01:00:00', 1, 0, 0, 1.0, 60.0, 3600, '1h'],
            ['01:30:00', 1, 30, 0, 1.5, 90.0, 5400, '1.5h'],
        ];
    }

    /**
     * @dataProvider getLoopArgs
     */
    public function testOutput(
        ?string $time,
        int $hours,
        int $minutes,
        int $seconds,
        float $totalHours,
        float $totalMinutes,
        int $totalSeconds,
        string $shortString
    ): void {
        $spentTime = new SpentTime($time);

        Assert::same($hours, $spentTime->getHours());
        Assert::same($minutes, $spentTime->getMinutes());
        Assert::same($seconds, $spentTime->getSeconds());
        Assert::same($totalHours, $spentTime->getTotalHours());
        Assert::same($totalMinutes, $spentTime->getTotalMinutes());
        Assert::same($totalSeconds, $spentTime->getTotalSeconds());
        Assert::same($shortString, $spentTime->getShortStringRepresentation());
    }

    public function testAdd(): void
    {
        $spentTime1 = new SpentTime('00:30:00');
        $spentTime2 = new SpentTime('01:15:00');

        Assert::same('01:45:00', (string) $spentTime1->add($spentTime2));
    }
}

(new SpentTimeTest)->run();
