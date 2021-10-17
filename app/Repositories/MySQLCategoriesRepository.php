<?php

namespace App\Repositories;

use App\Collections\CategoriesCollection;
use App\Models\Category;

class MySQLCategoriesRepository
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

    public function getAll(): CategoriesCollection
    {
        $categoriesCollection = new CategoriesCollection();
        $sql = "SELECT * FROM category";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($records as $record)
        {
            $categoriesCollection->add(new Category($record["name"]));
        }
        return $categoriesCollection;
    }
}
