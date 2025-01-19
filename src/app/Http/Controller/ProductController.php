<?php

namespace KpEsportes\App\Http\Controller;

use KpEsportes\App\Domain\Model\Product;
use KpEsportes\App\Domain\Service\AdminService;
use KpEsportes\App\Domain\Service\ProductService;
use KpEsportes\App\Http\Request;
use KpEsportes\App\Util\File;
use KpEsportes\App\Util\JWT;
use KpEsportes\App\Util\Validator;

class ProductController extends Controller {

    public Request $request;
    public ProductService $productService;
    public AdminService $adminService;
    public JWT $jwt;

    private $image_save_dir = __DIR__ . "/../../Storage/uploads/products/";

    public function __construct() {
        $this->request = new Request;
        $this->productService = new ProductService;
        $this->adminService = new AdminService;
        $this->jwt = new JWT;
    }

    public function addProduct() {
        Validator::validate([
            "name" => ["required", "min:3", "max:40"],
            "description" => ["required", "min:10", "max:10000"],
            "price" => ["required", "numeric"],
            "discount" => ["required", "numeric"],
            "size" => ["required", "filled", "list"],
            "image" => ["required", "file", "types:jpg,png,gif,jpeg"],
            "category" => ["required", "numeric", "exist:categories,category_id"],
        ]);

        $payload = $this->jwt->decodeToken($this->request->getHeader("Authorization"));
        $admin = $this->adminService->findByEmail($payload["email"]);

        $image = $this->request->getInput("image");

        $image_save_info = $this->saveImage($image);
        
        $product = new Product;

        $product->name = $this->request->getInput("name");
        $product->description = $this->request->getInput("description");
        $product->price = $this->request->getInput("price");
        $product->discount = $this->request->getInput("discount");
        $product->size = $this->request->getInput("size");
        $product->image = $image_save_info["name"] . "/" . $image_save_info["name"] . "." . $image_save_info["type"];
        $product->admin_id = $admin->admin_id;
        $product->category_id = $this->request->getInput("category");

        $this->productService->save($product);

        return [
            "message" => "produto salvo com sucesso",
        ];
    }

    public function deleteProduct($id) {
        Validator::validate([
            "id" => ["required", "numeric", "exist:products,product_id"],
        ], ["id" => $id]);

        $this->productService->deleteById($id);

        return [
            "message" => "Produto deletado com sucesso",
        ];
    }

    public function updateProduct($id) {
        Validator::validate([  
            "id" => ["required", "numeric", "exist:products,product_id"],
            "name" => ["required", "min:3", "max:40", "unique:products,name,product_id,$id"],
            "description" => ["required", "min:10", "max:10000"],
            "price" => ["required", "numeric"],
            "discount" => ["required", "numeric"],
            "size" => ["required", "filled", "list"],
            "image" => ["nullable", "file", "types:jpg,png,gif,jpeg"],
            "category" => ["required", "numeric", "exist:categories,category_id"],
        ], ["id" => $id]);

        $product = $this->productService->findById($id);
        
        $product->name = $this->request->getInput("name");
        $product->description = $this->request->getInput("description");
        $product->price = $this->request->getInput("price");
        $product->discount = $this->request->getInput("discount");
        $product->size = $this->request->getInput("size");
        $product->category_id = $this->request->getInput("category");

        if ($this->request->getInput("image") != null) {
            $image = $this->request->getInput("image");
            $image_save_info = $this->saveImage($image);

            unlink($this->image_save_dir . $product->image);
            rmdir($this->image_save_dir . explode("/", $product->image)[0]);

            $product->image = $image_save_info["name"] . "/" . $image_save_info["name"] . "." . $image_save_info["type"];
        }

        $this->productService->updateById($product);

        return [
            "message" => "Produto atualizado com sucesso",
        ];
    }

    public function getProducts() {
        Validator::validate([
            "limit" => ["nullable", "numeric"],
        ]);

        $binds = null;
        $where_clause = "ORDER BY created_at DESC";

        if ($this->request->getInput("limit") != null) {
            $where_clause .= " LIMIT :limit";
            $binds["limit"] = $this->request->getInput("limit");
        }

        return [
            "products" => $this->productService->select($where_clause, $binds),
        ];
    }

    public function searchProducts() {
        Validator::validate([
            "search" => ["nullable"],
        ]);

        $products = $this->productService->select("JOIN categories ON categories.category_id = products.category_id WHERE categories.name like :search OR products.name like :search ORDER BY created_at DESC", [
            "search" => "%" . $this->request->getInput("search") . "%",
        ], "products.*");

        return [
            "products" => $products,
        ];
    }

    public function findProduct($id) {
        Validator::validate([
            "id" => ["required", "numeric", "exist:products,product_id"],
        ], ["id" => $id]);

        $product = $this->productService->findById($id);

        return [
            "product" => $product,
        ];
    }

    public function searchWithPagination() {
        Validator::validate([
            "search" => ["nullable"],
            "products_per_page" => ["required", "numeric"],
            "page" => ["required", "numeric", "min:1"]
        ]);

        $search = $this->request->getInput("search");
        $limit = $this->request->getInput("products_per_page");
        $page = $this->request->getInput("page");

        $pages_count = round($this->productService->count() / $limit);

        $products = $this->productService->select("
            JOIN categories ON categories.category_id = products.category_id 
            WHERE categories.name like :search OR products.name like :search 
            ORDER BY created_at DESC LIMIT :limit OFFSET :offset
        ", [
            "search" => "%" . $search . "%",
            "limit" => $limit,
            "offset" => ($page - 1) * $limit,
        ], "products.*");

        return [
            "pages_count" => $pages_count,
            "products" => $products
        ];
    }

    public function countProducts() {
        return [
            "count_products" => $this->productService->count()
        ];
    }

    private function saveImage(array $image) {
        $image_save_name = time();
        $image_type = explode(".", $image["name"])[1];

        File::save($image["tmp_name"], $image_save_name . "." . $image_type);

        return [
            "name" => $image_save_name,
            "type" => $image_type,
        ];
    }

}