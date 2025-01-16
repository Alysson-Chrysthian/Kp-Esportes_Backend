<?php declare(strict_types=1);

use KpEsportes\App\Domain\Service\CategoryService;
use KpEsportes\App\Http\Controller\CategoryController;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Env;
use KpEsportes\App\Util\Hash;
use KpEsportes\App\Util\JWT;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class CategoryControllerTest extends TestCase {

    protected function setUp() : void {
        Env::load(".env.test");
        if (!extension_loaded(Env::get("DB_DRIVER")))
            $this->markTestSkipped();
    }

    public function testCanAddCategory() {
        $this->expectNotToPerformAssertions();

        $db = new SqlDatabase;
        $db->connect();

        $db->persist("INSERT INTO admins(name, email, password) VALUES(:name, :email, :password)", [
            "name" => "admin",
            "email" => "admin@admin.com",
            "password" => Hash::make("adminpass")
        ]);

        $db->close();

        $_REQUEST = [
            "name" => "sapatos",
        ];

        $jwt = new JWT;
        $token = $jwt->createToken([
            "name" => "admin",
            "email" => "admin@admin.com",
            "password" => Hash::make("adminpass")
        ]);

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method("getHeader")
            ->willReturn($token);   
        $mockRequest->method("getInput")
            ->willReturn("sapatos");
        
        $categoryController = new CategoryController;
        $categoryController->request = $mockRequest;
        $categoryController->addCategory();
    }

    public function testCanUpdateCategory() {
        $categoryService = new CategoryService;
        $category = $categoryService->findByName("sapatos");

        $_REQUEST["name"] = $category->name;
    
        $categoryController = new CategoryController;
        $response = $categoryController->updateCategory($category->category_id);
    
        $this->assertEquals($response["message"], "Categoria atualizada com sucesso");
    }

    #[Depends("testCanAddCategory")]
    public function testCanDeleteCategory() {
        $categoryService = new CategoryService;
        $category = $categoryService->findByName("sapatos");

        $categoryController = new CategoryController;
        $response = $categoryController->deleteCategory($category->category_id);
    
        $this->assertEquals($response["message"], "Categoria deletada com sucesso");
    
        $db = new SqlDatabase;
        $db->connect();

        $db->persist("DELETE FROM admins WHERE email = :email", [
            "email" => "admin@admin.com",
        ]);

        $db->close();
    }

}

