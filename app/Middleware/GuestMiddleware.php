<?php

namespace App\Middleware;

class GuestMiddleware implements Middleware
{
    public static function handle(): void
    {
        if (!isset($_SESSION["userId"]))
        {
            header("Location: /");
            exit;
        }
    }
}
