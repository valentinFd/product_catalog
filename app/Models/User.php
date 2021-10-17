<?php

namespace App\Models;

class User
{
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    private string $username;

    public function getUsername(): string
    {
        return $this->username;
    }

    private string $password;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function __construct(string $username, string $password, ?int $id = null)
    {
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->id = $id;
    }
}
