<?php

namespace KpEsportes\App\Domain\Model;

class Product extends Model {

    public int $product_id;
    public string $name;
    public string $description;
    public float $price;
    public string $size;
    public float $discount;
    public string $image;
    public int $admin_id;
    public int $category_id;

}