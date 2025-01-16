<?php declare(strict_types=1);

use KpEsportes\App\Util\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase {

    public function testCanAddHours() {
        $time = strtotime(date("Y-m-d") . " 14:20:10");
        $date = new Date($time);

        $hours = (int) $date->addHours(2)->format("H");
        $this->assertTrue($hours == (int) date("H", $time) + 2);
    }

}