<?php

namespace KpEsportes\App\Http\Controller;

use KpEsportes\App\Domain\Model\Category;
use KpEsportes\App\Domain\Service\AdminService;
use KpEsportes\App\Domain\Service\CategoryService;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Util\JWT;
use KpEsportes\App\Util\Validator;

class CategoryController extends Controller {

    public Request $request;
    public JWT $jwt;
    public AdminService $adminService;
    public CategoryService $categoryService;

    public function __construct() {
        $this->request = new Request;
        $this->jwt = new JWT;
        $this->adminService = new AdminService;
        $this->categoryService = new CategoryService;
    }

    public function addCategory() {
        Validator::validate([
            "name" => ["required", "min:3", "max:25", "unique:categories,name"],
        ]);

        $payload = $this->jwt->decodeToken($this->request->getHeader("Authorization"));
        $admin = $this->adminService->findByEmail($payload["email"]);

        $category = new Category;

        $category->name = $this->request->getInput("name");
        $category->admin_id = $admin->admin_id;

        $this->categoryService->save($category);

        return [
            "message" => "Categoria adicionada com sucesso",
        ];
    }

    public function deleteCategory($id) {
        Validator::validate([
            "id" => ["required", "numeric", "exist:categories,category_id"],
        ], ["id" => $id]);

        $this->categoryService->deleteById($id);

        return [
            "message" => "Categoria deletada com sucesso"
        ];
    }

    public function showAllCategories() {
        return [
            "categories" => $this->categoryService->getAll(),
        ];
    }

    public function updateCategory($id) {
        Validator::validate([
            "id" => ["required", "numeric", "exist:categories,category_id"],
            "name" => ["required", "min:3", "max:25", "unique:categories,name,category_id,$id"],
        ], ["id" => $id]);

        $category = new Category;

        $category->category_id = $id;
        $category->name = $this->request->getInput("name");

        $this->categoryService->update($category);

        return [
            "message" => "Categoria atualizada com sucesso",
        ];
        
    }

}