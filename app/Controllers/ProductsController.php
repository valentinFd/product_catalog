<?php

namespace App\Controllers;

use App\Exceptions\ProductException;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\MySQLCategoriesRepository;
use App\Repositories\MySQLProductsRepository;
use App\Repositories\MySQLUsersRepository;
use App\Services\Categories\GetCategoriesService;
use App\Services\Products\CreateProductService;
use App\Services\Products\DeleteProductService;
use App\Services\Products\GetProductsService;
use App\Services\Products\UpdateProductService;
use App\Services\Users\GetUserService;
use App\Validators\CreateProductValidator;
use App\View;

class ProductsController
{

    private GetProductsService $getProductsService;

    private GetUserService $getUserService;

    private GetCategoriesService $getCategoriesService;

    private CreateProductService $createProductService;

    private UpdateProductService $updateProductService;

    private DeleteProductService $deleteProductService;

    public function __construct(GetProductsService   $getProductsService,
                                GetUserService       $getUserService,
                                GetCategoriesService $getCategoriesService,
                                CreateProductService $createProductService,
                                UpdateProductService $updateProductService,
                                DeleteProductService $deleteProductService
    )
    {
        $this->getProductsService = $getProductsService;
        $this->getUserService = $getUserService;
        $this->getCategoriesService = $getCategoriesService;
        $this->createProductService = $createProductService;
        $this->updateProductService = $updateProductService;
        $this->deleteProductService = $deleteProductService;
    }

    public function index(): View
    {
        $user = $this->getUserService->execute($_SESSION["userId"]);
        $categoriesCollection = $this->getCategoriesService->execute();
        $category = $categoriesCollection->search($_GET["category"]) ?? new Category("all");
        $productsCollection = $this->getProductsService->execute($category);
        return new View("products.twig", [
            "products" => $productsCollection->getProducts(),
            "categories" => $categoriesCollection->getCategories(),
            "selectedCategory" => $category,
            "username" => $user->getUsername()
        ]);
    }

    public function add(): View
    {
        $categoriesCollection = $this->getCategoriesService->execute();
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
            $this->createProductService->execute($_POST["name"], $_POST["category"], (int)$_POST["quantity"], $_SESSION["userId"]);
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
        $productsCollection = $this->getProductsService->execute(new Category("all"));
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
        $categoriesCollection = $this->getCategoriesService->execute();

        $productsCollection = $this->getProductsService->execute(new Category("all"));
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
            $productsCollection = $this->getProductsService->execute(new Category("all"));
            $product = $productsCollection->search($id);
            $this->updateProductService->execute($product, $_POST["name"], $_POST["category"], (int)$_POST["quantity"]);
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
        $productsCollection = $this->getProductsService->execute(new Category("all"));
        $product = $productsCollection->search($id);
        $this->deleteProductService->execute($product);
        header("Location: /products");
    }
}
