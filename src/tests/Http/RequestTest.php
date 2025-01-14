<?php declare(strict_types=1);

use KpEsportes\App\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase {

    protected function setUp() : void {
        $_REQUEST["email"] = "mymail@mail.com";
        function getallheaders() {
            return [];
        }
    }

    public function testCanGetInput() {    
        $request = new Request;
        $this->assertEquals("mymail@mail.com", $request->getInput("email"));
    }

}
