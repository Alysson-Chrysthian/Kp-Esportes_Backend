<?php declare(strict_types=1);

use KpEsportes\App\Util\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase {

    public function testCanAddHours() {
        $hours = (int) Date::now()->addHours(2)->format("h");
        $this->assertTrue($hours == (int) date("h") + 2);
    }

}