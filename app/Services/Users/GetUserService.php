<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\MySQLUsersRepository;

class GetUserService
{
    private MySQLUsersRepository $usersRepository;

    public function __construct(MySQLUsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function execute(int $id): User
    {
        return $this->usersRepository->get($id);
    }
}
