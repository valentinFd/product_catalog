<?php

namespace App\Services\Products;

use App\Collections\ProductsCollection;
use App\Models\Category;
use App\Repositories\MySQLProductsRepository;

class GetProductsService
{
    private MySQLProductsRepository $productsRepository;

    public function __construct(MySQLProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function execute(Category $category): ProductsCollection
    {
        return $this->productsRepository->getAll($category);
    }
}
