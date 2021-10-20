<?php

namespace App\Controllers;

use App\Exceptions\ProductException;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\MySQLCategoriesRepository;
use App\Repositories\MySQLProductsRepository;
use App\Repositories\MySQLUsersRepository;
use App\Validators\CreateProductValidator;
use App\View;

class ProductsController
{
    private MySQLCategoriesRepository $categoriesRepository;

    private MySQLProductsRepository $productsRepository;

    private MySQLUsersRepository $usersRepository;

    public function __construct()
    {
        try
        {
            $this->categoriesRepository = new MySQLCategoriesRepository();
        }
        catch (\PDOException $e)
        {
            echo $e->getMessage();
        }
        $this->productsRepository = new MySQLProductsRepository();
        $this->usersRepository = new MySQLUsersRepository();
    }

    public function index(): View
    {
        $user = $this->usersRepository->get($_SESSION["userId"]);
        $categoriesCollection = $this->categoriesRepository->getAll();

        $category = $categoriesCollection->search($_GET["category"]) ?? new Category("all");

        $productsCollection = $this->productsRepository->getAll($category);
        return new View("products.twig", [
            "products" => $productsCollection->getProducts(),
            "categories" => $categoriesCollection->getCategories(),
            "selectedCategory" => $category,
            "username" => $user->getUsername()
        ]);
    }

    public function add(): View
    {
        $categoriesCollection = $this->categoriesRepository->getAll();
        return new View("create_product.twig", [
            "categories" => $categoriesCollection->getCategories(),
            "errors" => $_SESSION["errors"]
        ]);
    }

    public function create(): void
    {
        try
        {
            $validator = new CreateProductValidator();
            $validator->validate($_POST);
            $this->productsRepository->create(new Product($_POST["name"], $_POST["category"], $_POST["quantity"], $_SESSION["userId"]));
            header("Location: /products");
        }
        catch (ProductException $e)
        {
            $_SESSION["errors"] = $validator->getErrors();
            header("Location: /products/add");
        }
    }

    public function show(int $id): View
    {
        $productsCollection = $this->productsRepository->getAll(new Category("all"));
        $product = $productsCollection->search($id);
        if ($product === null) return new View("404_not_found.twig");
        else
        {
            return new View("show_product.twig", [
                "product" => $product
            ]);
        }
    }

    public function edit(int $id): View
    {
        $categoriesCollection = $this->categoriesRepository->getAll();

        $productsCollection = $this->productsRepository->getAll(new Category("all"));
        $product = $productsCollection->search($id);
        if ($product === null) return new View("404_not_found.twig");
        else
        {
            return new View("edit_product.twig", [
                "product" => $product,
                "categories" => $categoriesCollection->getCategories(),
                "errors" => $_SESSION["errors"]
            ]);
        }
    }

    public function update(int $id): void
    {
        try
        {
            $validator = new CreateProductValidator();
            $validator->validate($_POST);
            $productsCollection = $this->productsRepository->getAll(new Category("all"));
            $product = $productsCollection->search($id);
            $this->productsRepository->update($product, $_POST["name"], $_POST["category"], (int)$_POST["quantity"]);
            header("Location: /products/$id/edit");
        }
        catch (ProductException $e)
        {
            $_SESSION["errors"] = $validator->getErrors();
            header("Location: /products/$id/edit");
        }
    }

    public function delete(int $id): void
    {
        $productsCollection = $this->productsRepository->getAll(new Category("all"));
        $product = $productsCollection->search($id);
        $this->productsRepository->delete($product);
        header("Location: /products");
    }
}
