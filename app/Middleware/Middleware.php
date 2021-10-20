<?php

namespace App\Middleware;

interface Middleware
{
    public static function handle(): void;
}
