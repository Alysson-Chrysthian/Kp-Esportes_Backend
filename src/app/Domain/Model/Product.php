<?php

namespace KpEsportes\App\Domain\Model;

class Product extends Model {

    public int $product_id;
    public string $name;
    public string $description;
    public float $price;
    public string|array $size;
    public float $discount;
    public string $image;
    public int $admin_id;
    public int $category_id;
    
    public ?Category $category;

    public function setUp() {
        if ($this->category_id != null)
            $this->category = $this->category();
        $this->size = json_decode($this->size, true) ?? [];
    }

    public function category() {
        return $this->belongTo(Category::class, "categories", "category_id", "category_id");
    }

}