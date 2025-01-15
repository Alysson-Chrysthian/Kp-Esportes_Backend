<?php declare(strict_types=1);

use KpEsportes\App\Http\Middleware\GuestMiddleware;
use KpEsportes\App\Http\Request;
use PHPUnit\Framework\TestCase;

class GuestMiddlewareTest extends TestCase {

    public function testGuestMiddlewareIsThrowing() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Você não pode acessar esta rota estando autenticado");

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method("getHeader")
            ->willReturn("Bearer token");
        
        $guestMiddleware = new GuestMiddleware;
        $guestMiddleware->request = $mockRequest;

        $guestMiddleware->handle();
    }

    public function testGuestMiddlewareIsWorking() {
        $this->expectNotToPerformAssertions();

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method("getHeader")
            ->willReturn(null);

        $guestMiddleware = new GuestMiddleware;
        $guestMiddleware->request = $mockRequest;

        $guestMiddleware->handle();
    }

}