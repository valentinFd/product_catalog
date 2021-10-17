<?php

namespace App\Collections;

use App\Models\Category;

class CategoriesCollection
{
    private array $categories;

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function __construct()
    {
        $this->categories = [];
    }

    public function add(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function search(?string $name): ?Category
    {
        if ($name !== null)
        {
            foreach ($this->categories as $category)
            {
                if ($name === $category->getName()) return $category;
            }
        }
        return null;
    }
}
