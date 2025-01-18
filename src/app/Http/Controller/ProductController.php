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
        $image_save_name = time();
        $image_type = explode(".", $image["name"])[1];

        File::save($image["tmp_name"], $image_save_name . "." . $image_type);
        
        $product = new Product;

        $product->name = $this->request->getInput("name");
        $product->description = $this->request->getInput("description");
        $product->price = $this->request->getInput("price");
        $product->discount = $this->request->getInput("discount");
        $product->size = $this->request->getInput("size");
        $product->image = $image_save_name . "/" . $image_save_name . "." . $image_type;
        $product->admin_id = $admin->admin_id;
        $product->category_id = $this->request->getInput("category");

        $this->productService->save($product);

        return [
            "message" => "produto salvo com sucesso",
        ];
    }

}