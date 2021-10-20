<?php

use App\Middleware\GuestMiddleware;
use App\Middleware\LoggedInMiddleware;

session_start();

require_once("vendor/autoload.php");

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r)
{
    $r->addRoute("GET", "/", "App\Controllers\UsersController@index");
    $r->addRoute("POST", "/", "App\Controllers\UsersController@logIn");
    $r->addRoute("POST", "/logOut", "App\Controllers\UsersController@logOut");
    $r->addRoute("GET", "/sign-up", "App\Controllers\UsersController@signUp");
    $r->addRoute("POST", "/sign-up", "App\Controllers\UsersController@create");

    $r->addRoute("GET", "/products", "App\Controllers\ProductsController@index");
    $r->addRoute("GET", "/products/add", "App\Controllers\ProductsController@add");
    $r->addRoute("POST", "/products/add", "App\Controllers\ProductsController@create");
    $r->addRoute("GET", "/products/{id}", "App\Controllers\ProductsController@show");
    $r->addRoute("GET", "/products/{id}/edit", "App\Controllers\ProductsController@edit");
    $r->addRoute("POST", "/products/{id}/edit", "App\Controllers\ProductsController@update");
    $r->addRoute("POST", "/products/{id}/delete", "App\Controllers\ProductsController@delete");
});

$httpMethod = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];

if (false !== $pos = strpos($uri, "?"))
{
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$loader = new \Twig\Loader\FilesystemLoader("app/Views");
$twig = new \Twig\Environment($loader);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0])
{
    case FastRoute\Dispatcher::NOT_FOUND:
        echo $twig->render("404_not_found.twig");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo $twig->render("405_not_allowed.twig");
        break;
    case FastRoute\Dispatcher::FOUND:
        switch ($routeInfo[1])
        {
            case "App\Controllers\UsersController@index":
            case "App\Controllers\UsersController@logIn":
            case "App\Controllers\UsersController@logOut":
            case "App\Controllers\UsersController@signUp":
            case "App\Controllers\UsersController@create":
                LoggedInMiddleware::handle();
                break;
            case "App\Controllers\ProductsController@index":
            case "App\Controllers\ProductsController@add":
            case "App\Controllers\ProductsController@create":
            case "App\Controllers\ProductsController@show":
            case "App\Controllers\ProductsController@edit":
            case "App\Controllers\ProductsController@update":
            case "App\Controllers\ProductsController@delete":
                GuestMiddleware::handle();
                break;
        }
        [$controller, $method] = explode("@", $routeInfo[1]);
        $vars = $routeInfo[2];
        $response = (new $controller())->$method(...array_values($vars));
        if (is_a($response, "App\View"))
        {
            echo $twig->render($response->getTemplate(), $response->getArgs());
            unset($_SESSION["errors"]);
        }
        break;
}
