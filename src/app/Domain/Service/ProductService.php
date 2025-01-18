<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Domain\Model\Product;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Date;

class ProductService extends Service {

    public SqlDatabase $db;
    protected string $table = "products";

    public function __construct() {
        $this->db = new SqlDatabase;
    }

    public function save(Product $product) {
        $this->db->connect();

        $this->db->persist("
            INSERT INTO " . $this->table . "(name, description, price, size, discount, image, admin_id, category_id, created_at, updated_at)
            VALUES(:name, :desc, :price, :size, :discount, :image, :admin_id, :category_id, :created_at, :updated_at)
        ", [
            "name" => $product->name,
            "desc" => $product->description,
            "price" => $product->price,
            "size" => $product->size,
            "discount" => $product->discount,
            "image" => $product->image,
            "admin_id" => $product->admin_id,
            "category_id" => $product->category_id,
            "created_at" => Date::now()->format(),
            "updated_at" => Date::now()->format(),
        ]);

        $this->db->close();
    }

    public function deleteById(int $id) {
        $this->db->connect();
        $rows_affected = $this->db->persist("DELETE FROM " . $this->table . " WHERE product_id = :id", [
            "id" => $id,
        ]);
        $this->db->close();

        return $rows_affected;
    }

    public function updateById(Product $product) {
        $this->db->connect();
        
        $rows_affected = $this->db->persist("
            UPDATE " . $this->table . "  
            SET name = :name, description = :desc, price = :price, discount = :disc, size = :size, image = :image, category_id = :cat, updated_at = :updated_at
            WHERE product_id = :id 
        ", [
            "id" => $product->product_id,
            "name" => $product->name,
            "desc" => $product->description,
            "price" => $product->price,
            "disc" => $product->discount,
            "size" => $product->size,
            "image" => $product->image,
            "cat" => $product->category_id,
            "updated_at" => Date::now()->format(),
        ]);

        $this->db->close();

        return $rows_affected;
    }

    public function findById(int $id) {
        $this->db->connect();
        $product = $this->db->fetchFirst("SELECT * FROM " . $this->table . " WHERE product_id = :id", Product::class, [
            "id" => $id,
        ]);
        $this->db->close();

        $product->setUp();

        return $product;
    }

    public function select(string $whereClause, array|null $binds = null, string $select_elements = "*") {
        $this->db->connect();

        $products = $this->db->fetch("SELECT $select_elements FROM " . $this->table . " $whereClause", Product::class, $binds);

        foreach ($products as $product)
            $product->setUp();

        $this->db->close();

        return $products;
    }

}