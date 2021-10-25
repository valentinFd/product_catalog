<?php

namespace App\Controllers;

use App\Exceptions\LogInException;
use App\Exceptions\SignUpException;
use App\Models\User;
use App\Repositories\MySQLUsersRepository;
use App\Services\Users\CreateUserService;
use App\Validators\SignUpValidator;
use App\View;

class UsersController
{
    private MySQLUsersRepository $usersRepository;

    private CreateUserService $createUserService;

    public function __construct(MySQLUsersRepository $usersRepository, CreateUserService $createUserService)
    {
        $this->usersRepository = $usersRepository;
        $this->createUserService = $createUserService;
    }

    public function index(): View
    {
        return new View("index.twig", [
            "errors" => $_SESSION["errors"]
        ]);
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
        return new View("sign_up.twig", [
            "errors" => $_SESSION["errors"]
        ]);
    }

    public function create(): void
    {
        try
        {
            $validator = new SignUpValidator();
            $validator->validate($_POST);
            $this->createUserService->execute($_POST["username"], $_POST["password"]);
            header("Location: /");
        }
        catch (SignUpException $e)
        {
            $_SESSION["errors"] = $validator->getErrors();
            header("Location: /sign-up");
        }
    }
}
