<?php declare(strict_types=1);

use KpEsportes\App\Util\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase {

    public function testCanValidateEmail() {
        $this->expectNotToPerformAssertions();

        Validator::validate([
            "email" => ["email"],
        ], [
            "email" => "mymail@mail.com"
        ]);
    }

    public function testIsThrowing() {
        $this->expectException(Exception::class);

        Validator::validate([
            "email" => ["email"],
        ], [
            "email" => "myinvalidmail"
        ]);
    }

    public function testMinMaxIsWorking() {
        Validator::validate([
            "name" => ["min:4", "max:6"],
        ], [
            "name" => "name",
        ]);

        $this->expectException(Exception::class);
        Validator::validate([
            "name" => ["min:4", "max:6"],
        ], [
            "name" => "nam",
        ]);

        Validator::validate([
            "name" => ["min:4", "max:6"],
        ], [
            "name" => "nametolong",
        ]);
    }

}