<?php

namespace App\Collections;

use App\Models\Product;

class ProductsCollection
{
    private array $products;

    public function getProducts(): array
    {
        return $this->products;
    }

    public function __construct()
    {
        $this->products = [];
    }

    public function add(Product $product): void
    {
        $this->products[] = $product;
    }

    public function search(int $id): ?Product
    {
        foreach ($this->products as $product)
        {
            if ($product->getId() === $id) return $product;
        }
        return null;
    }
}
