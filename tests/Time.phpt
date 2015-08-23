<?php

use Tester\Assert;
use Sunfox\DateUtils\Time;


require __DIR__ . '/bootstrap.php';


$time = new Time('12');
Assert::same(43200, $time->getSeconds());
Assert::same(720.0, $time->getMinutes());
Assert::same(12.0, $time->getHours());
Assert::same('12:00:00', $time->getTime());
Assert::same('12h', $time->getTime('H\h'));

$time = new Time('12:24');
Assert::same(44640, $time->getSeconds());
Assert::same(744.0, $time->getMinutes());
Assert::same(12.4, $time->getHours());
Assert::same('12:24:00', $time->getTime());
Assert::same('12h24m', $time->getTime('H\hi\m'));

$time = new Time('12:24:15');
Assert::same(44655, $time->getSeconds());
Assert::same(744.25, $time->getMinutes());
Assert::same(12.4, $time->getHours());
Assert::same('12:24:15', $time->getTime());
Assert::same('12h24m15s', $time->getTime('H\hi\ms\s'));

$time = new Time('2:24:15');
Assert::same(8655, $time->getSeconds());
Assert::same(144.25, $time->getMinutes());
Assert::same(2.4, $time->getHours());
Assert::same('02:24:15', $time->getTime());
Assert::same('2h24m15s', $time->getTime('G\hi\ms\s'));

$time = new Time('1');
Assert::same(3600, $time->getSeconds());
Assert::same(60.0, $time->getMinutes());
Assert::same(1.0, $time->getHours());
Assert::same('01:00:00', $time->getTime());
Assert::same('1h', $time->getTime('G\h'));

$time = new Time('1h');
Assert::same(3600, $time->getSeconds());
Assert::same(60.0, $time->getMinutes());
Assert::same(1.0, $time->getHours());
Assert::same('01:00:00', $time->getTime());
Assert::same('01h', $time->getTime('H\h'));

$time = new Time('1.5');
Assert::same(5400, $time->getSeconds());
Assert::same(90.0, $time->getMinutes());
Assert::same(1.5, $time->getHours());
Assert::same('01:30:00', $time->getTime());
Assert::same('01h30m', $time->getTime('H\hi\m'));

$time = new Time('1.5h');
Assert::same(5400, $time->getSeconds());
Assert::same(90.0, $time->getMinutes());
Assert::same(1.5, $time->getHours());
Assert::same('01:30:00', $time->getTime());
Assert::same('01h30m', $time->getTime('H\hi\m'));

$time = new Time('12h24');
Assert::same(44640, $time->getSeconds());
Assert::same(744.0, $time->getMinutes());
Assert::same(12.4, $time->getHours());
Assert::same('12:24:00', $time->getTime());
Assert::same('12h24m', $time->getTime('H\hi\m'));

$time = new Time('12h24m');
Assert::same(44640, $time->getSeconds());
Assert::same(744.0, $time->getMinutes());
Assert::same(12.4, $time->getHours());
Assert::same('12:24:00', $time->getTime());
Assert::same('12h24m', $time->getTime('H\hi\m'));

$time = new Time('12h24m15');
Assert::same(44655, $time->getSeconds());
Assert::same(744.25, $time->getMinutes());
Assert::same(12.4, $time->getHours());
Assert::same('12:24:15', $time->getTime());
Assert::same('12h24m15s', $time->getTime('H\hi\ms\s'));

$time = new Time('12h24m15s');
Assert::same(44655, $time->getSeconds());
Assert::same(744.25, $time->getMinutes());
Assert::same(12.4, $time->getHours());
Assert::same('12:24:15', $time->getTime());
Assert::same('12h24m15s', $time->getTime('H\hi\ms\s'));

Assert::exception(function () {
	new Time('aaa');
}, 'InvalidArgumentException', 'Cannot parse time value');
