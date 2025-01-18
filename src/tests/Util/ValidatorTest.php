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

    public function testCanValidateList() {
        $this->expectNotToPerformAssertions();
        Validator::validate([
            "list" => ["list"],
        ], ["list" => []]);
        Validator::validate([
            "list" => ["list"],
        ], ["list" => "{\"list\":\"list\"}"]);
    }

    public function testValidationListIsThrowingToNonJsonStrings() {
        $this->expectException(Exception::class);
        Validator::validate([
            "list" => ["list"]
        ], ["list" => "not a json"]);
    }

    public function testValidationListIsThrowingToNonArrayVars() {
        $this->expectException(Exception::class);
        Validator::validate([
            "list" => ["list"],
        ], ["list" => new stdClass]);
    }

    public function testCanValidateFilledValues() {
        $this->expectNotToPerformAssertions();
        Validator::validate([
            "filled" => ["filled"],
        ], ["filled" => "fill"]);
        Validator::validate([
            "filled" => ["filled"]
        ], ["filled" => ["filledarray"]]);
    }

    public function testFilledValidationIsThrowingToEmptyArrays() {
        $this->expectException(Exception::class);
        Validator::validate([
            "filled" => ["filled"]
        ], ["filled" => []]);
    }

    public function testFilledValidationIsThrowingToEmptyStrings() {
        $this->expectException(Exception::class);
        Validator::validate([
            "filled" => ["filled"]
        ], ["filled" => ""]);
    }

}