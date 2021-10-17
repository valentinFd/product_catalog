<?php

namespace App\Models;

class Category
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function __construct($name)
    {
        $this->name = $name;
    }
}
