<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Repositories\MySQLProductsRepository;

class DeleteProductService
{
    private MySQLProductsRepository $productsRepository;

    public function __construct(MySQLProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(Product $product): void
    {
        $this->productsRepository->delete($product);
    }
}
