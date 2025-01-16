<?php declare(strict_types=1);

use KpEsportes\App\Http\Middleware\Auth;
use KpEsportes\App\Http\Request;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase {

    public function testCanPassAuthMiddleware() {
        $this->expectNotToPerformAssertions();

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method("getHeader")
            ->willReturn("my_auth_token");

        $authMiddleware = new Auth;
        $authMiddleware->request = $mockRequest;
        $authMiddleware->handle();
    }

    public function testAuthMiddlewareIsThrowing() {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(403);

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method("getHeader")
            ->willReturn(null);

        $authMiddleware = new Auth;
        $authMiddleware->request = $mockRequest;
        $authMiddleware->handle();
    }

}