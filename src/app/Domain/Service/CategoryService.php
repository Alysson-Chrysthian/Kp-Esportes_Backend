<?php

namespace KpEsportes\App\Domain\Service;

use KpEsportes\App\Domain\Model\Category;
use KpEsportes\App\Storage\SqlDatabase;
use KpEsportes\App\Util\Date;

class CategoryService extends Service {

    protected string $table = "categories";

    public function __construct() {
        $this->db = new SqlDatabase;
    }

    public function save(Category $category) {
        $this->db->connect();

        $this->db->persist("
            INSERT INTO " . $this->table . "(name, admin_id, created_at, updated_at)
            VALUES(:name, :admin_id, :created_at, :updated_at)    
        ", [
            "name" => $category->name,
            "admin_id" => $category->admin_id,
            "created_at" => Date::now()->format(),
            "updated_at" => Date::now()->format(),
        ]);

        $this->db->close();
    }

    public function deleteByName(string $name) {
        $this->db->connect();

        $category = $this->db->fetchFirst("SELECT * FROM " . $this->table . " WHERE name = :name", Category::class, [
            "name" => $name,
        ]);
        
        $this->db->persist("DELETE FROM products WHERE category_id = :id", [
            "id" => $category->category_id,
        ]);
        $rows_affected = $this->db->persist("DELETE FROM " . $this->table . " WHERE name = :name", [
            "name" => $name,
        ]);

        $this->db->close();

        return $rows_affected;
    }

    public function deleteById(int $id) {
        $this->db->connect();

        $this->db->persist("DELETE FROM products WHERE category_id = :id", [
            "id" => $id,
        ]);
        $rows_affected = $this->db->persist("DELETE FROM " . $this->table . " WHERE category_id = :id", [
            "id" => $id,
        ]);

        $this->db->close();
        
        return $rows_affected;
    }

    public function findByName(string $name) {
        $this->db->connect();

        $category = $this->db->fetchFirst("SELECT * FROM " . $this->table . " WHERE name = :name", Category::class, [
            "name" => $name,
        ]);

        $this->db->close();

        return $category;
    }

    public function getAll() {
        $this->db->connect();
        $categories = $this->db->fetch("SELECT * FROM " . $this->table, Category::class);
        $this->db->close();

        return $categories;
    }

    public function update(Category $category) {
        $this->db->connect();

        $rows_affected = $this->db->persist("UPDATE categories SET name = :name, updated_at = :updated_at WHERE category_id = :id", [
            "name" => $category->name,
            "updated_at" => Date::now()->format(),
            "id" => $category->category_id,
        ]);

        $this->db->close();

        return $rows_affected;
    }

}