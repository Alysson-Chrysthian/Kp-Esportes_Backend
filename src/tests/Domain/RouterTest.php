<?php declare(strict_types=1);

use KpEsportes\App\Domain\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

    public function testCanAddEndPoints() {
        Router::add("GET", "/falseendpoint/{id}", function () {});
        $endpoints = Router::getEndpoints();

        $this->assertTrue(isset($endpoints["GET"]["/api/falseendpoint/(.*)"]));
    }

    public function testCheckRouteIsWorking() {
        Router::add("GET", "/falseendpoint/{id}", function ($id) {
            return $id;
        });

        $_SERVER["REQUEST_URI"] = "/api/falseendpoint/12";
        $_SERVER["REQUEST_METHOD"] = "GET";
        
        $this->assertEquals(12, Router::checkRoute());
    }

}