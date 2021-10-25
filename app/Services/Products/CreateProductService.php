<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Repositories\MySQLProductsRepository;

class CreateProductService
{
    private MySQLProductsRepository $productsRepository;

    public function __construct(MySQLProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(string $name, string $category, int $quantity, int $userId): void
    {
        $this->productsRepository->create(new Product($name, $category, $quantity, $userId));
    }
}
