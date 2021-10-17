<?php

namespace App\Validators;

use App\Exceptions\ProductException;

class CreateProductValidator
{
    private array $errors;

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function __construct()
    {
        $this->errors = [];
    }

    public function validate(array $fields): void
    {
        if (strlen(trim($fields["name"])) === 0)
        {
            $this->errors[] = "Product name cannot be empty";
        }
        if ($fields["quantity"] < 1)
        {
            $this->errors[] = "Quantity cannot be less than 1";
        }
        if (!empty($this->errors)) throw new ProductException();
    }
}
