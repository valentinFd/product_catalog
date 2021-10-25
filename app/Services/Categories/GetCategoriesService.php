<?php

namespace App\Services\Categories;

use App\Collections\CategoriesCollection;
use App\Repositories\MySQLCategoriesRepository;

class GetCategoriesService
{
    private MySQLCategoriesRepository $categoriesRepository;

    public function __construct(MySQLCategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function execute(): CategoriesCollection
    {
        return $this->categoriesRepository->getAll();
    }
}
