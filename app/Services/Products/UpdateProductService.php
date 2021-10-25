<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Repositories\MySQLProductsRepository;

class UpdateProductService
{
    private MySQLProductsRepository $productsRepository;

    public function __construct(MySQLProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(Product $product, string $name, string $category, int $quantity): void
    {
        $this->productsRepository->update($product, $name, $category, $quantity);
    }
}
