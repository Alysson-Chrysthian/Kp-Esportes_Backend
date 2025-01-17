<?php declare(strict_types=1);

use KpEsportes\App\Domain\Model\Admin;
use KpEsportes\App\Domain\Model\Category;
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

    private CategoryController $categoryController;
    private SqlDatabase $sqlDatabase;
    private JWT $jwt;
    private CategoryService $categoryService;

    private string $adminName;
    private string $adminEmail;
    private string $adminPassword;

    private Admin $admin;

    protected function setUp(): void {
        Env::load(".env.test");
        if (!extension_loaded("pgsql")) 
            $this->markTestSkipped();

        $this->categoryController = new CategoryController;
        $this->sqlDatabase = new SqlDatabase;
        $this->jwt = new JWT;
        $this->categoryService = new CategoryService;
    
        $this->adminName = "admin";
        $this->adminEmail = "admin@admin.com";
        $this->adminPassword = "adminpassword";

        $this->sqlDatabase->connect();

        $this->sqlDatabase->persist("INSERT INTO admins(name, email, password) VALUES(:name, :email, :password)", [
            "name" => $this->adminName,
            "email" => $this->adminEmail,
            "password" => Hash::make($this->adminPassword)
        ]);

        $this->admin = $this->sqlDatabase->fetchFirst("SELECT * FROM admins WHERE name = :name AND email = :email", Admin::class, [
            "name" => $this->adminName,
            "email" => $this->adminEmail,
        ]);
    }

    protected function tearDown(): void {
        $this->resetData();
        $this->sqlDatabase->close();
    }
    
    private function resetData() {
        $this->sqlDatabase->persist("DELETE FROM categories WHERE name = :name OR name = :second_name", ["name" => "sapatos", "second_name" => "tenis"]);
        $this->sqlDatabase->persist("DELETE FROM admins WHERE email = :email", ["email" => $this->adminEmail]);
    }

    private function createToken() {
        return $this->jwt->createToken(["email" => $this->adminEmail]);
    }

    public function testCanAddCategory() {
        $_REQUEST = ["name" => "sapatos"];
        
        $mockRequest = $this->createMock(Request::class);
        $mockRequest->method("getHeader")
            ->willReturn($this->createToken());
        $mockRequest->method("getInput")
            ->willReturn($_REQUEST["name"]);
        
        $this->categoryController->request = $mockRequest;
        $this->categoryController->addCategory();

        $response = $this->sqlDatabase->fetchFirst("SELECT * FROM categories WHERE name = :name", Category::class, [
            "name" => "sapatos",
        ]);

        $this->assertNotNull($response);
        $this->assertEquals(Category::class, get_class($response));
    }

    public function testCanDeleteCategory() {
        $this->sqlDatabase->persist("INSERT INTO categories(name, admin_id) VALUES(:name, :admin_id)", [
            "name" => "sapatos",
            "admin_id" => $this->admin->admin_id,
        ]);
        $category = $this->sqlDatabase->fetchFirst("SELECT * FROM categories WHERE name = :name", Category::class, [
            "name" => "sapatos",
        ]);

        $this->categoryController->deleteCategory($category->category_id);

        $this->assertNull($this->sqlDatabase->fetchFirst("SELECT * FROM categories WHERE name = :name", Category::class, [
            "name" => "sapatos",
        ]));
    }
    public function testCanUpdateCategory() {
        $this->sqlDatabase->persist("INSERT INTO categories(name, admin_id) VALUES(:name, :admin_id)", [
            "name" => "sapatos",
            "admin_id" => $this->admin->admin_id,
        ]);
        $category = $this->sqlDatabase->fetchFirst("SELECT * FROM categories WHERE name = :name", Category::class, [
            "name" => "sapatos",
        ]);

        $_REQUEST = [
            "name" => "tenis",
        ];

        $this->categoryController->request = new Request; 
        $this->categoryController->updateCategory($category->category_id);

        $categoryUpdated = $this->sqlDatabase->fetchFirst("SELECT * FROM categories WHERE category_id = :id", Category::class, [
            "id" => $category->category_id,
        ]);

        $this->assertEquals($categoryUpdated->name, "tenis");
    }

    public function testCanFetchCategory() {
        $this->sqlDatabase->persist("INSERT INTO categories(name, admin_id) VALUES(:name, :admin_id)", [
            "name" => "sapatos",
            "admin_id" => $this->admin->admin_id,
        ]);

        $response = $this->categoryController->showAllCategories();

        $this->assertArrayHasKey("categories", $response);
        $this->assertEquals(Category::class, get_class($response["categories"][0]));
    }

}

