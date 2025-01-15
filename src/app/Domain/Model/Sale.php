<?php

namespace KpEsportes\App\Domain\Model;

class Sale extends Model {

    public int $sale_id;
    public string $size;
    public int $client_id;
    public int $product_id;

}