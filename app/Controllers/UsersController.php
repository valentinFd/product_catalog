<?php

namespace App\Controllers;

use App\Exceptions\LogInException;
use App\Exceptions\SignUpException;
use App\Models\User;
use App\Repositories\MySQLUsersRepository;
use App\Validators\SignUpValidator;
use App\View;

class UsersController
{
    private MySQLUsersRepository $usersRepository;

    public function __construct()
    {
        try
        {
            $this->usersRepository = new MySQLUsersRepository();
        }
        catch (\PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function index(): View
    {
        if (empty($_SESSION["userId"]))
        {
            return new View("index.twig", [
                "errors" => $_SESSION["errors"]
            ]);
        }
        else
        {
            header("Location: /products");
        }
    }

    public function logIn(): void
    {
        try
        {
            $this->usersRepository->validate($_POST["username"], $_POST["password"]);
            header("Location: /products");
        }
        catch (LogInException $e)
        {
            $_SESSION["errors"][] = $e->getMessage();
            header("Location: /");
        }
    }

    public function logOut(): void
    {
        session_destroy();
        header("Location: /");
    }

    public function signUp(): View
    {
        if (empty($_SESSION["userId"]))
        {
            return new View("sign_up.twig", [
                "errors" => $_SESSION["errors"]
            ]);
        }
        else
        {
            header("Location: /products");
        }
    }

    public function create(): void
    {
        try
        {
            $validator = new SignUpValidator();
            $validator->validate($_POST);
            $this->usersRepository->create(new User($_POST["username"], $_POST["password"]));
            header("Location: /");
        }
        catch (SignUpException $e)
        {
            $_SESSION["errors"] = $validator->getErrors();
            header("Location: /sign-up");
        }
    }
}
