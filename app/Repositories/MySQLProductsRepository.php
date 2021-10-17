<?php

namespace App\Repositories;

use App\Collections\ProductsCollection;
use App\Models\Category;
use App\Models\Product;

class MySQLProductsRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        try
        {
            $this->pdo = new \PDO("mysql:host=localhost;dbname=db;charset=UTF8", "root", "");
        }
        catch (\PDOException $e)
        {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getAll(Category $category): ProductsCollection
    {
        $productsCollection = new ProductsCollection();
        $categoryName = $category->getName();
        $sql = "SELECT * FROM product WHERE user_id = ?"
            . ($categoryName === "all" ? "" : " AND category = '$categoryName'")
            . " ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$_SESSION["userId"]]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($records as $record)
        {
            $productsCollection->add(new Product(
                $record["name"],
                $record["category"],
                (int)$record["quantity"],
                (int)$record["user_id"],
                (int)$record["id"],
                $record["created_at"],
                $record["updated_at"]
            ));
        }
        return $productsCollection;
    }

    public function create(Product $product): void
    {
        $sql = "INSERT INTO product (name, category, quantity, user_id) VALUES(?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product->getName(), $product->getCategory(), $product->getQuantity(), $product->getUserId()]);
    }

    public function update(Product $product, string $name, string $category, int $quantity): void
    {
        $sql = "UPDATE product SET name = ?, category = ?, quantity = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $category, $quantity, $product->getId()]);
    }

    public function delete(Product $product): void
    {
        $sql = "DELETE FROM product WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$product->getId()]);
    }
}
