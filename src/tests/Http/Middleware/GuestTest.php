<?php declare(strict_types=1);

use KpEsportes\App\Http\Middleware\Guest;
use KpEsportes\App\Http\Request;
use PHPUnit\Framework\TestCase;

class GuestTest extends TestCase {

    public function testGuestMiddlewareIsWorking() {
        $this->expectNotToPerformAssertions();
        
        $requestMock = $this->createMock(Request::class);
        $requestMock->method("getHeader")
            ->willReturn(null);
        
        $guest = new Guest;
        $guest->request = $requestMock;

        $guest->handle();
    }

    public function testGuestMiddlewareIsThorwing() {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(403);

        $requestMock = $this->createMock(Request::class);
        $requestMock->method("getHeader")
            ->willReturn("Bearer token");
        
        $guest = new Guest;
        $guest->request = $requestMock;

        $guest->handle();
    }

}