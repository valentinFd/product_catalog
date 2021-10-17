<?php

namespace App\Validators;

use App\Exceptions\SignUpException;
use App\Repositories\MySQLUsersRepository;

class SignUpValidator
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
        if ((new MySQLUsersRepository())->exists($fields["username"]))
        {
            $this->errors[] = "This username is already taken";
        }
        if (strpos($fields["username"], " ") !== false)
        {
            $this->errors[] = "Username cannot contain whitespaces";
        }
        if (strlen(trim($fields["username"])) < 5)
        {
            $this->errors[] = "Username must be at least 5 characters long";
        }
        if (strlen(trim($fields["password"])) < 8)
        {
            $this->errors[] = "Password must be at least 8 characters long";
        }
        if ($fields["password"] !== $fields["passwordConfirm"])
        {
            $this->errors[] = "Passwords do not match";
        }
        if (!empty($this->errors)) throw new SignUpException();
    }
}
