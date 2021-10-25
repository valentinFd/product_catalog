<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\MySQLUsersRepository;

class CreateUserService
{
    private MySQLUsersRepository $usersRepository;

    public function __construct(MySQLUsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function execute(string $username, string $password): void
    {
        $this->usersRepository->create(new User($username, $password));
    }
}
