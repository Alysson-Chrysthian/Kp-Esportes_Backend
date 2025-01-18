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

}