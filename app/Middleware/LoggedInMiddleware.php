<?php

namespace App\Middleware;

class LoggedInMiddleware implements Middleware
{
    public static function handle(): void
    {
        if (isset($_SESSION["userId"]))
        {
            header("Location: /products");
            exit;
        }
    }
}
