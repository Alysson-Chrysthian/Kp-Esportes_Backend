<?php

namespace KpEsportes\App\Domain\Model;

class Avaliation extends Model {

    public int $avaliation_id;
    public string $commentary;
    public int $stars;
    public int $product_id;
    public int $client_id;

}