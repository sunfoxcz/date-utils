<?php declare(strict_types=1);

namespace SunfoxTests\DateUtils\Cases;

use InvalidArgumentException;
use Sunfox\DateUtils\SpentTime;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class SpentTimeTest extends Tester\TestCase
{
    protected function getLoopArgs(): array
    {
        return [
            [NULL, TRUE, 0, 0, 0, 0.0, 0.0, 0, '0m'],
            ['00:00:00', TRUE, 0, 0, 0, 0.0, 0.0, 0, '0m'],
            ['01:00:00', FALSE, 1, 0, 0, 1.0, 60.0, 3600, '1h'],
            ['01:30:00', FALSE, 1, 30, 0, 1.5, 90.0, 5400, '1.5h'],
        ];
    }

    /**
     * @dataProvider getLoopArgs
     */
    public function testOutput(
        ?string $time,
        bool $isZero,
        int $hours,
        int $minutes,
        int $seconds,
        float $totalHours,
        float $totalMinutes,
        int $totalSeconds,
        string $shortString
    ): void {
        $spentTime = new SpentTime($time);

        Assert::same($isZero, $spentTime->isZero());
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
        $spentTime1 = new SpentTime('01:30:30');
        $spentTime2 = new SpentTime('01:45:30');

        Assert::same('03:16:00', (string) $spentTime1->add($spentTime2));
    }

    public function testInvalidInput(): void
    {
        Assert::exception(function () {
            new SpentTime('bad format');
        }, InvalidArgumentException::class, "Bad time format: 'bad format'.");
    }
}

(new SpentTimeTest)->run();
